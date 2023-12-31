<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\controllers;

use Craft;
use craft\commerce\helpers\DebugPanel;
use craft\commerce\helpers\Locale as LocaleHelper;
use craft\commerce\models\Pdf;
use craft\commerce\Plugin;
use craft\commerce\records\Pdf as PdfRecord;
use craft\helpers\Json;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Class Pdfs Controller
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.2
 */
class PdfsController extends BaseAdminController
{
    /**
     * @since 3.2
     */
    public function actionIndex(): Response
    {
        $pdfs = Plugin::getInstance()->getPdfs()->getAllPdfs();
        return $this->renderTemplate('commerce/settings/pdfs/index', compact('pdfs'));
    }

    /**
     * @param int|null $id
     * @param Pdf|null $pdf
     * @throws HttpException
     * @since 3.2
     */
    public function actionEdit(int $id = null, Pdf $pdf = null): Response
    {
        $variables = compact('pdf', 'id');

        $pdfLanguageOptions = [
            PdfRecord::LOCALE_ORDER_LANGUAGE => Craft::t('commerce', 'The language the order was made in.'),
        ];

        $variables['pdfLanguageOptions'] = array_merge($pdfLanguageOptions, LocaleHelper::getSiteAndOtherLanguages());

        if (!$variables['pdf']) {
            if ($variables['id']) {
                $variables['pdf'] = Plugin::getInstance()->getPdfs()->getPdfById($variables['id']);

                if (!$variables['pdf']) {
                    throw new HttpException(404);
                }
            } else {
                $variables['pdf'] = new Pdf();
            }
        }

        if ($variables['pdf']->id) {
            $variables['title'] = $variables['pdf']->name;
        } else {
            $variables['title'] = Craft::t('commerce', 'Create a new PDF');
        }

        DebugPanel::prependOrAppendModelTab(model: $variables['pdf'], prepend: true);

        return $this->renderTemplate('commerce/settings/pdfs/_edit', $variables);
    }

    /**
     * @throws BadRequestHttpException
     * @throws ErrorException
     * @throws Exception
     * @throws NotSupportedException
     * @throws ServerErrorHttpException
     * @since 3.2
     */
    public function actionSave(): ?Response
    {
        $this->requirePostRequest();

        $pdfsService = Plugin::getInstance()->getPdfs();
        $pdfId = $this->request->getBodyParam('id');

        if ($pdfId) {
            $pdf = $pdfsService->getPdfById($pdfId);
            if (!$pdf) {
                throw new BadRequestHttpException("Invalid PDF ID: $pdfId");
            }
        } else {
            $pdf = new Pdf();
        }

        // Shared attributes
        $pdf->name = $this->request->getBodyParam('name');
        $pdf->handle = $this->request->getBodyParam('handle');
        $pdf->description = $this->request->getBodyParam('description');
        $pdf->templatePath = $this->request->getBodyParam('templatePath');
        $pdf->fileNameFormat = $this->request->getBodyParam('fileNameFormat');
        $pdf->enabled = $this->request->getBodyParam('enabled');
        $pdf->isDefault = $this->request->getBodyParam('isDefault');
        $pdf->language = $this->request->getBodyParam('language');

        // Save it
        if ($pdfsService->savePdf($pdf)) {
            $this->setSuccessFlash(Craft::t('commerce', 'PDF saved.'));
            return $this->redirectToPostedUrl($pdf);
        } else {
            $this->setFailFlash(Craft::t('commerce', 'Couldn’t save PDF.'));
        }

        // Send the model back to the template
        Craft::$app->getUrlManager()->setRouteParams(['pdf' => $pdf]);

        return null;
    }

    /**
     * @throws HttpException
     * @since 3.2
     */
    public function actionDelete(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $id = $this->request->getRequiredBodyParam('id');

        Plugin::getInstance()->getPdfs()->deletePdfById($id);
        return $this->asSuccess();
    }

    /**
     * @throws \yii\db\Exception
     * @throws BadRequestHttpException
     * @since 3.2
     */
    public function actionReorder(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();
        $ids = Json::decode($this->request->getRequiredBodyParam('ids'));

        if (!Plugin::getInstance()->getPdfs()->reorderPdfs($ids)) {
            return $this->asFailure(Craft::t('commerce', 'Couldn’t reorder PDFs.'));
        }

        return $this->asSuccess();
    }
}

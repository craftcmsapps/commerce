if (typeof Craft.Commerce === typeof undefined) {
  Craft.Commerce = {};
}

/**
 * Class Craft.Commerce.VariantValuesInput
 */
Craft.Commerce.VariantValuesInput = Craft.BaseInputGenerator.extend({
  startListening: function () {
    if (this.listening) {
      return;
    }

    this.listening = true;

    this.addListener(this.$source, 'textchange', 'onTextChange');
    this.addListener(this.$form, 'submit', 'onFormSubmit');
  },
  updateTarget: function () {
    var sourceVal = this.$source.val();
    var targetVal = this.generateTargetValue(sourceVal);
    this.$target.prop('checked', true);
  },
  onFormSubmit: function () {
    if (this.timeout) {
      clearTimeout(this.timeout);
    }
  },
});

<template>
    <div id="order-save" class="order-flex">
        <template v-if="editing">
            <input
                type="hidden"
                name="orderData"
                id="test"
                v-model="orderData"
            />
            <input
                id="order-save-btn"
                type="button"
                class="btn submit"
                :value="$options.filters.t('Update order', 'commerce')"
                :class="{disabled: !hasCustomer || recalculateLoading}"
                :disabled="!hasCustomer || recalculateLoading"
                @click="save()"
            />

            <div class="spacer"></div>
        </template>

        <div>
            <div
                class="btn menubtn"
                data-icon="settings"
                :title="$options.filters.t('Actions', 'commerce')"
                ref="updateMenuBtn"
            ></div>

            <div class="menu">
                <template v-if="editing && hasCustomer && hasAddresses">
                    <ul>
                        <li>
                            <a @click="save({redirect: ordersIndexUrl})">
                                <option-shortcut-label
                                    os="mac"
                                    shortcut-key="S"
                                ></option-shortcut-label>
                                {{
                                    'Save and return to all orders'
                                        | t('commerce')
                                }}
                            </a>
                        </li>
                    </ul>
                </template>
                <template v-if="draft && !draft.order.isCompleted">
                    <ul>
                        <li>
                            <a href="#" @click.prevent="copy()">
                                {{ 'Share cart…' | t('commerce') }}
                            </a>
                        </li>
                    </ul>
                </template>

                <template v-if="canDelete">
                    <template v-if="editing && hasCustomer && hasAddresses">
                        <hr />
                    </template>

                    <ul>
                        <li>
                            <a class="error" @click="deleteOrder()">{{
                                'Delete' | t('commerce')
                            }}</a>
                        </li>
                    </ul>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    /* global Garnish, $, Craft */

    import {mapGetters, mapState} from 'vuex';
    import OptionShortcutLabel from './OptionShortcutLabel';
    import mixins from '../../mixins';

    export default {
        components: {OptionShortcutLabel},

        mixins: [mixins],

        computed: {
            ...mapState({
                draft: (state) => state.draft,
                saveLoading: (state) => state.saveLoading,
                recalculateLoading: (state) => state.recalculateLoading,
                editing: (state) => state.editing,
            }),

            ...mapGetters([
                'orderId',
                'canDelete',
                'hasAddresses',
                'hasCustomer',
            ]),

            ordersIndexUrl() {
                return window.orderEdit.ordersIndexUrlHashed;
            },

            orderData: {
                get() {
                    return this.$store.state.orderData;
                },

                set(value) {
                    this.$store.commit('updateOrderData', value);
                },
            },
        },

        methods: {
            save(options) {
                if (
                    options !== undefined &&
                    options.hasOwnProperty('redirect')
                ) {
                    $('#main-form input[type="hidden"][name="redirect"]').val(
                        options.redirect
                    );
                }

                this.saveOrder(this.draft);
            },

            deleteOrder() {
                const message = 'Are you sure you want to delete this order?';

                if (window.confirm(message)) {
                    this.$store
                        .dispatch('deleteOrder', this.orderId)
                        .then(() => {
                            this.returnToOrders();
                        });
                }
            },

            copy() {
                if (
                    !this.draft ||
                    !this.draft.order ||
                    !this.draft.order.loadCartUrl
                ) {
                    this.$store.dispatch(
                        'displayError',
                        this.$options.filters.t(
                            'Unable to retrieve load cart URL',
                            'commerce'
                        )
                    );
                } else {
                    Craft.ui.createCopyTextPrompt({
                        label: this.$options.filters.t(
                            'Copy the URL',
                            'commerce'
                        ),
                        instructions: this.$options.filters.t(
                            'This URL will load the cart into the user’s session, making it the active cart.',
                            'commerce'
                        ),
                        value: this.draft.order.loadCartUrl,
                    });
                }
            },

            returnToOrders() {
                window.location.replace(window.orderEdit.ordersIndexUrl);
            },
        },

        mounted() {
            new Garnish.MenuBtn(this.$refs.updateMenuBtn);
        },
    };
</script>

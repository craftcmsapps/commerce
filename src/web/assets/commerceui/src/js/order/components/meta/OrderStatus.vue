<template>
    <div>
        <div>
            <a class="btn menubtn order-status-btn" ref="orderStatus">
                <template v-if="orderStatus.color">
                    <span
                        class="status"
                        :class="{[orderStatus.color]: true}"
                    ></span>
                </template>
                <template v-else>
                    <span class="status"></span>
                </template>

                <span class="order-status-btn-name">{{
                    orderStatus.name
                }}</span>
            </a>

            <div class="menu">
                <ul class="padded" role="listbox">
                    <li v-for="(status, key) in orderStatuses" :key="key">
                        <a
                            :data-id="status.id"
                            :data-color="status.color"
                            :data-name="status.name"
                            :class="{sel: orderStatus.id === status.value}"
                        >
                            <span
                                class="status"
                                :class="{[status.color]: true}"
                            ></span>
                            <span>{{ status.name }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <template v-if="originalOrderStatusId !== orderStatusId">
            <div class="order-status-message">
                <textarea
                    ref="textarea"
                    class="text"
                    :placeholder="$options.filters.t('Message', 'commerce')"
                    v-model="message"
                    maxlength="10000"
                ></textarea>

                <input
                    id="orderedit-suppress-emails"
                    class="checkbox"
                    type="checkbox"
                    v-model="suppressEmails"
                /><label for="orderedit-suppress-emails">{{
                    $options.filters.t('Suppress emails', 'commerce')
                }}</label>
            </div>
        </template>
    </div>
</template>

<script>
    /* global Garnish */

    import {mapGetters} from 'vuex';

    export default {
        props: {
            order: {
                type: Object,
            },
            originalOrderStatusId: {
                type: Number,
            },
            suppressEmails: {
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                isRecalculating: false,
                textareaHasFocus: false,
                orderMessage: '',
                originalMessage: null,
            };
        },

        computed: {
            ...mapGetters(['orderStatuses']),

            orderStatus() {
                if (this.orderStatusId !== 0) {
                    for (let orderStatusesKey in this.orderStatuses) {
                        const orderStatus =
                            this.orderStatuses[orderStatusesKey];

                        if (orderStatus.id == this.orderStatusId) {
                            return orderStatus;
                        }
                    }
                }

                return {
                    id: 0,
                    name: this.$options.filters.t('None', 'commerce'),
                    color: null,
                };
            },

            orderStatusId: {
                get() {
                    return this.order.orderStatusId;
                },

                set(value) {
                    if (value == this.originalOrderStatusId) {
                        this.message = this.originalMessage;
                    }

                    const order = JSON.parse(JSON.stringify(this.order));
                    order.orderStatusId = value;
                    this.$emit('updateOrder', order);
                },
            },

            message: {
                get() {
                    return this.orderMessage;
                },

                set(value) {
                    this.orderMessage = value;
                    this.$store.commit('updateDraftOrderMessage', value);
                },
            },
        },

        watch: {
            suppressEmails(newVal, oldVal) {
                this.$store.commit('updateDraftSuppressEmails', newVal);
            },
        },

        methods: {
            onSelectStatus(status) {
                if (status.dataset.id === 0) {
                    this.orderStatusId = null;
                } else {
                    this.message = null;
                    this.orderStatusId = parseInt(status.dataset.id);
                }
            },
        },

        mounted() {
            this.originalMessage = this.order.message;
            new Garnish.MenuBtn(this.$refs.orderStatus, {
                onOptionSelect: this.onSelectStatus,
            });
        },
    };
</script>

<style lang="scss">
    .order-status-btn {
        max-width: 100%;
    }

    .order-status-btn-name {
        overflow: hidden;
        text-overflow: ellipsis;
        width: calc(100% - 31px);
    }

    .order-status-message {
        margin-top: 10px;
        width: 100%;
    }
</style>

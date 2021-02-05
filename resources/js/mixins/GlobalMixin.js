export default {
    data() {
        return {
            org: {},
            user: {},
            addresses: [],
            defaultAddress: null
        }
    },
    created() {
        this.org = Vue.prototype.$org
        this.user = Vue.prototype.$user
        this.addresses = Vue.prototype.$addresses
        this.addresses.forEach((address) => {
            if (address.IsDefault === "1") {
                this.defaultAddress = address;
            }
        })
    },
    mounted() {
        Bus.$on('update-default-address', (addresses) => {
            this.addresses = addresses;
            addresses.forEach((address) => {
                if (address.IsDefault === "1") {
                    this.defaultAddress = address;
                }
            })
        })
    },
    methods: {
        checkLang(data) {
            let parse = JSON.parse(data);
            if (parse.hasOwnProperty('en')) {
                if (window._locale === 'en') {
                    return parse.en;
                }
                if (parse.hasOwnProperty('ar')) {
                    return parse.ar;
                }
                return "-";
            }
            return parse;
        },
        getIndex(array, conditionFn) {
            const item = array.find(conditionFn)
            return array.indexOf(item)
        },
        exist(array, conditionFn) {
            return array.some(conditionFn);
        },
        fireAlert(title, message, success = true) {
            Swal.fire({
                title: title,
                text: message,
                icon: success ? 'success' : 'warning',
                confirmButtonText: this.trans('close'),
                showConfirmButton: false,
                timer: 1200
            });
        },
        numberFormat(number, decimals = 0, decPoint, thousandsSep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
            const n = !isFinite(+number) ? 0 : +number
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
            const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
            const dec = (typeof decPoint === 'undefined') ? '.' : decPoint
            let s = ''
            const toFixedFix = function (n, prec) {
                if (('' + n).indexOf('e') === -1) {
                    return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
                } else {
                    const arr = ('' + n).split('e')
                    let sig = ''
                    if (+arr[1] + prec > 0) {
                        sig = '+'
                    }
                    return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
                }
            }
            // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || ''
                s[1] += new Array(prec - s[1].length + 1).join('0')
            }
            return s.join(dec)
        }
    },
    computed: {
        selectedAddress() {
            if (this.defaultAddress !== null) {
                let address = this.defaultAddress;
                return `${address.Name} - ${address.CityName},${address.Line1},${address.Line2},${address.AptNumber}`
            }
            return "-";
        },
        isAuthed() {
            if (Object.keys(this.user).length > 0) {
                return true;
            }
            return false;
        }
    },
}

export default {
    data() {
        return {}
    },
    methods: {
        nextStep(nextStep, item, skip = false) {
            let url = "/checkout/confirm-step";
            if (skip) {
                url = "/checkout/skip-step";
            }
            const urlParams = new URLSearchParams(window.location.search);
            item.key = urlParams.get('step');
            let formData = new FormData();
            for (let key in item) {
                if (item.hasOwnProperty(key)) {
                    if (Array.isArray(item[key])) {
                        let i = 0;
                        for (const item of item[key]) {
                            formData.append(key + '[' + i + ']', JSON.stringify(item));
                            i++;
                        }
                    } else if (typeof item[key] === 'object') {
                        formData.append(key, JSON.stringify(item[key]));
                    } else {
                        formData.append(key, item[key]);
                    }
                }
            }
            axios.post(url, formData).then((response) => {
                if (nextStep !== null) {
                    if(nextStep.ArrayName === "Wallet"){
                        window.location.href = `/checkout?step=${nextStep.NextRouteObj.ArrayName}`;
                    }else{
                        window.location.href = `/checkout?step=${nextStep.ArrayName}`;
                    }
                }
                if (!skip) {
                    Bus.$emit('step-confirmed', response);
                }
            }).catch((error) => {
                console.log(error);
            });

        },
    }
}

export default {
    data() {
        return {}
    },
    methods: {
        toggleFavorite(item, isCustomized = false) {
            let postUrl = "/favorite/set-favourite";
            if (item.IsFavorite === '1') {
                item.IsFavorite = '0';
                item.isCustomized = isCustomized;
                postUrl = "/favorite/remove-favourite"
            } else {
                item.IsFavorite = '1';
                item.isCustomized = isCustomized;
            }

            item.Modifiers.forEach((modifier) => {
                modifier.details.items.forEach((modifierItem) => {
                    if (!modifierItem.hasOwnProperty('isSelected')) {
                        modifierItem.isSelected = false;
                    }
                });
            });

            let data = {
                item_id: item.ID,
                favorite_name: null,
                item: item,
            }

            console.log("Un favorite item",data)

            let formData = new FormData();
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    if (Array.isArray(data[key])) {
                        let i = 0;
                        for (const item of data[key]) {
                            formData.append(key + '[' + i + ']', JSON.stringify(item));
                            i++;
                        }
                    } else if (typeof data[key] === 'object') {
                        formData.append(key, JSON.stringify(data[key]));
                    } else {
                        formData.append(key, data[key]);
                    }
                }
            }
            axios.post(postUrl, formData).then((response) => {
                Bus.$emit('reload-data',response.data);
            }).catch((error) => {
                console.log(error);
            }).finally(() => {
                console.log("Json Toggle Fav", item)
            });
        },
    }
}

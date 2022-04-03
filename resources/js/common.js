import {mapGetters} from "vuex";

export default {
    data() {
        return {}
    },
    methods:
    {
        async callApi(method, url, dataObj, headers) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj,
                    headers: headers
                });
            } catch (e) {
                return e.response.data.errors
            }
        },
        topNotice(title, icon) {
            var toastMixin = this.$swal.mixin({
                toast: true,
                icon: 'success',
                title: 'General Title',
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', this.$swal.stopTimer)
                    toast.addEventListener('mouseleave', this.$swal.resumeTimer)
                }
            });
            toastMixin.fire({
                animation: true,
                title: title,
                icon: icon
            });

        },
        bottomNotice(title, icon) {
            var toastMixinBottom = this.$swal.mixin({
                toast: true,
                icon: 'success',
                title: 'Posted successfully',
                animation: false,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', this.$swal.stopTimer)
                    toast.addEventListener('mouseleave', this.$swal.resumeTimer)
                }
            })
            toastMixinBottom.fire({
                title: title,
                icon: icon
            })
        },
        deleteAlert(title, url, obj, msg, icon, img, header, flag) {
            this.$swal.fire({
                title: title,
                icon: 'question',
                iconHtml: '؟',
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر',
                showCancelButton: true,
                showCloseButton: true,
                // type: "warning",
                // confirmButtonColor: "#3085d6",
            }).then(async (result) => {
                if (result.value) {
                    const res = await this.callApi('post', url, obj);
                    if (res.status === 200) {
                        this.topNotice(msg, icon);
                        this.$store.commit('setDeleteFlag', true);
                        if (flag) this.deleteOperation(header, img)
                    } else this.topNotice('عملیات ناموفق!', 'error')
                }
            });
        },
        async deleteOperation(header, img) {
            const res = await this.callApi('post', 'app/delete_image', img, header)
            if (res.status !== 200) this.bottomNotice('خطا در عملیات حذف در سرور!', 'error');
        },
        checkUserPermission(key) {
            if (!this.userPermission) return true;
            let isPermitted = false;
            let search = null;
            for (let d of this.userPermission) {
                switch (key) {
                    case 'visiter':
                        search = d.visiter;
                        break;
                    case 'writer':
                        search = d.writer;
                        break;
                    case 'changer':
                        search = d.changer;
                        break;
                    case 'remover':
                        search = d.remover;
                        break
                }
                if (this.$route.path === `/${d.permission.route}` || this.$route.name === d.permission.name)
                {
                    if (search) {
                        isPermitted = true
                        break;
                    } else break;
                }
            }
            return isPermitted;
        },
        checkDynamicPermission(key) {
            if (!this.userPermission) return true;
            let isPermitted = false;
            let search = null;
            for (let d of this.userPermission) {
                switch (key) {
                    case 'visiter':
                        search = d.visiter;
                        break;
                    case 'writer':
                        search = d.writer;
                        break;
                    case 'changer':
                        search = d.changer;
                        break;
                    case 'remover':
                        search = d.remover;
                        break
                }
                if (this.route === d.permission.route) {
                    if (search) {
                        isPermitted = true
                        break;
                    } else break;
                }
            }
            return isPermitted;
        },
    },
        computed: {
            ...mapGetters({
                'userPermission': 'getUserPermission',
                'route': 'getRoute',
            }),

            isReadPermitted() {
                return this.checkUserPermission('visiter');
            },
            isWritePermitted() {
                return this.checkUserPermission('writer');
            },
            isUpdatePermitted() {
                return this.checkUserPermission('changer');
            },
            isDeletePermitted() {
                return this.checkUserPermission('remover');
            },
            isPermittedDynamicRead() {
                return this.checkDynamicPermission('visiter');
            },
        }
}

<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-4 ">
            <button type="button" class="btn btn-purple _box_shadow my-3 justify-content-start" v-if="isWritePermitted" @click="adding">
                <i class='bx bxs-user-plus'></i>  افزودن تبلیغ
            </button>
            <table class="table _box_shadow table-bordered border-white table-hover">
                <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>متن</th>
                    <th>عکس</th>
                    <th>لینک تبلیغ</th>
                    <th>تاریخ ایجاد</th>
                    <th>بیشتر</th>
                </tr>
                </thead>
                <tbody style="background: transparent">
                <tr  v-for="(a, i) in ads" :key="i" v-if="ads.length">
                    <td >{{a.id}}</td>
                    <td>{{ a.name }}</td>
                    <td>{{ a.title }}</td>
                    <td><img :src="a.image" width="40px" height="40px" @click="magnifyImage(a)" style="cursor: pointer"/></td>
                    <td>{{ a.link }}</td>
                    <td>{{ a.updated_at }}</td>
                    <td style=" text-align: center; vertical-align:middle">
                        <div class="dropdown">
                            <i class='bx bx-dots-horizontal '
                               id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" style="background: #F7F6FB;box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);" aria-labelledby="dropdownMenuButton1">
                                <li v-if="isDeletePermitted">
                                    <a class="dropdown-item" @click="deletting(a.id,i,a.image)" style="float: right;text-align:right" href="#">حذف تبلیغ</a>
                                </li>
                                <li v-if="isUpdatePermitted">
                                    <a class="dropdown-item" @click="editting(a,i)" style="float: right;text-align:right" href="#">تغییر تبلیغ</a>
                                </li>
                            </ul>
                        </div>
                    </td>

                </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "ad",
    data(){
        return{
            permissionToDelete:false,
            token:'',
            ads:[],
            add:{
                title:'',
                link:'',
                image:'',
                name:''
            },
            edit:{
                id:null,
                title:'',
                link:'',
                image:'',
                name:''
            },
            deletee:{
                id:null
            },
            i:null,
            header:{
                headers: {"X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content}
            }
        }
    },
    computed:
        {
            ...mapGetters(['getDeleteFlag'])
        },
    watch:
        {
            getDeleteFlag(obj){
                if (obj) {
                    this.ads.splice(this.i,1);
                    this.permissionToDelete=true;
                    this.$store.commit('setDeleteFlag', false);
                }
            }
        },
    methods:{
        async adding() {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات تبلیغ را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام تبلیغ" id="swal-inputn" class="swal2-input">
                    <input type="url" placeholder="لینک تبلیغ" id="swal-inputl" class="swal2-input">
                    <input type="text" placeholder="متن تبلیغ" id="swal-inputt" class="swal2-input"><br><br>
                    <input type="file" placeholder="عکس تبلیغ" id="swal-inputi" class="form-control"
                    accept=".png, .jpg, .jpeg, .svg" />
                  `,
                focusConfirm: false,

                preConfirm: async () => {
                    const file =document.getElementById('swal-inputi').files[0];
                    if (file) {
                        let image = new FormData();
                        image.append('file', file);
                        const resUpload =await this.callApi('post','app/upload',image)
                        if (resUpload.status === 200) this.add.image= `/images/ads/${resUpload.data}`
                        else return this.topNotice('خطا در آپلود عکس!','error')
                    }
                    this.add.name = document.getElementById('swal-inputn').value;
                    this.add.link = document.getElementById('swal-inputl').value;
                    this.add.title = document.getElementById('swal-inputt').value;

                    if (this.add.name.trim() === '') return this.topNotice('لطفا نام تبلیغ را وارد کنید.', 'error')
                    if (this.add.link.trim() === '') return this.topNotice('لطفا لینک تبلیغ را وارد کنید.', 'error')
                    if (this.add.title.trim() === '') return this.topNotice('لطفا تیتر تبلیغ را وارد کنید.', 'error')
                    if (this.add.image.trim() === '') return this.topNotice('لطفا عکس تبلیغ را وارد کنید.', 'error')


                    const res = await this.callApi('post', 'ads/create_ad', this.add,this.header)
                    if (res.status === 201) {
                        this.ads.unshift(res.data)
                        return this.topNotice('تبلیغ با موفقیت ثبت شد!', 'success')
                    } else {
                        if (res.status === 422) {
                            for (let i in res.data.errors) {
                                return this.topNotice(res.data.errors[i][0], 'error')
                            }
                        } else return this.topNotice('مشکلی پیش آمد مجددا تلاش کنید.', 'error')

                    }
                }
            });
        },
        async editting(ad,i) {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات تبلیغ را برای ویرایش وارد کنید.',
                confirmButtonText: 'ثبت تغییرات',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام تبلیغ" id="swal2-inputn" class="swal2-input" value="${ad.name}">
                    <input type="url" placeholder="لینک تبلیغ" id="swal2-inputl" class="swal2-input" value="${ad.link}">
                    <input type="text" placeholder="متن تبلیغ" id="swal2-inputt" class="swal2-input" value="${ad.title}"><br><br>
                    <img src="${ad.image}" height="80px" width="80px" id="swal2-img" style="cursor: pointer"/><br>
                    <input type="file" placeholder="عکس تبلیغ" id="swal2-inputi" class="form-control"
                    accept=".png, .jpg, .jpeg, .svg" />
                  `,
                focusConfirm: false,

                preConfirm: async () => {
                    this.edit.id=ad.id
                    this.edit.name = document.getElementById('swal2-inputn').value;
                    this.edit.link = document.getElementById('swal2-inputl').value;
                    this.edit.title = document.getElementById('swal2-inputt').value;

                    if (this.edit.name.trim() === '') return this.topNotice('لطفا نام تبلیغ را وارد کنید.', 'error')
                    if (this.edit.link.trim() === '') return this.topNotice('لطفا لینک تبلیغ را وارد کنید.', 'error')
                    if (this.edit.title.trim() === '') return this.topNotice('لطفا تیتر تبلیغ را وارد کنید.', 'error')
                    const file =document.getElementById('swal2-inputi').files[0];
                    if (file) {
                        let image = new FormData();
                        image.append('file', file);
                        const resUpload =await this.callApi('post','app/upload',image)
                        if (resUpload.status === 200) {
                            this.edit.image= `/images/ads/${resUpload.data}`;
                            const imgDel ={imageName:ad.image}
                            const resDelete =await this.callApi('post','app/delete_image',imgDel,this.header)
                            document.getElementById('swal2-img').source=resDelete.data
                        }
                        else {
                             this.bottomNotice('عکس جدیدی انتخاب نشد!','warning');
                            this.edit.image = ad.image;
                        }
                    }else {
                        this.edit.image = ad.image;
                         this.topNotice('عکس جدیدی انتخاب نشد!','warning');
                    }
                    if (this.edit.image.trim() === '') return this.topNotice('لطفا عکس تبلیغ را وارد کنید.', 'error')


                    const res = await this.callApi('post', 'ads/edit_ad', this.edit,this.header)
                    if (res.status === 200)
                    {
                        this.ads[i].image = this.edit.image;
                        this.ads[i].title = this.edit.title;
                        this.ads[i].name = this.edit.name;
                        this.ads[i].link = this.edit.link;
                        return this.topNotice('تبلیغ با موفقیت تغییر یافت!', 'success');
                    } else {
                        if (res.status === 422) {
                            for (let i in res.data.errors) {
                                return this.topNotice(res.data.errors[i][0], 'error')
                            }
                        } else return this.topNotice('مشکلی پیش آمد مجددا تلاش کنید.', 'error')

                    }
                }
            });

        },
        async deletting(id, i, image) {
            this.deletee.id = id
            this.deleteAlert('آیا از حذف این تبلیغ اطمینان دارید؟', 'ads/delete_ad', this.deletee, 'تبلیغ موردنظر با موفقیت حذف شد!', 'success')
            this.i = i
            const img = {imageName: image}
            const res = await this.callApi('post','app/delete_image',img,this.header)
            if (this.permissionToDelete) {
                this.i = i
                const img = {imageName: brand.image}
                const res = await this.callApi('post', 'app/delete_image', img, this.header)
                if (res.status !== 200) this.bottomNotice('خطا در عملیات حذف در سرور!', 'error')
            }
        },
        magnifyImage(a)
        {
            this.$swal.fire({
                imageUrl: a.image,
                // imageHeight: 1500,
                imageAlt: a.name
            })
        }
    },
    async created(){
        // this.token =  window.Laravel.csrfToken
        const res = await this.callApi('get','ads/get_ads');
        console.log(res.data)
        if(res.status === 200){
            this.ads = res.data;

        }else {
            this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!','error');
        }
    }
}
</script>

<style scoped>
._box_shadow {
    -webkit-box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);
    box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);
}
ul li:hover{
    background: #AFA5D9;
}
.btn-purple{
    border-color:#4723D9;
    transition: all 0.4s ease-in;
    float: left;
    color: #4723D9;
}
.btn-purple:hover{
    background: #4723D9;
    color: #F7F6FB;
}
</style>

ookhhh mmkhto mikhrmmmmaaaaa
hmsrmmmmmmmmmmmmmmm=))))))))))))))))))
chikart knm akhe ba mzeye nanaeie mnnnn=))
(xd

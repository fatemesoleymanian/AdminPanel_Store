<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-4 ">
            <button type="button" class="btn btn-purple _box_shadow my-3 " @click="adding" v-if="isWritePermitted">
                <i class='bx bxs-user-plus'></i>  افزودن نقش
            </button>
            <table class="table _box_shadow table-bordered border-white table-hover">
                <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                <tr>
                    <th>#</th>
                    <th>نقش</th>
                    <th>تاریخ ایجاد</th>
                    <th>بیشتر</th>
                </tr>
                </thead>
                <tbody style="background: transparent">
                <tr  v-for="(r, i) in roles" :key="i" v-if="roles.length">
                    <td >{{r.id}}</td>
                    <td>{{ r.roleName }}</td>
                    <td>{{ r.created_at }}</td>
                    <td style=" text-align: center; vertical-align:middle">
                        <div class="dropdown">
                            <i class='bx bx-dots-horizontal '
                               id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" style="background: #F7F6FB;box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);" aria-labelledby="dropdownMenuButton1">
                                <li v-if="isDeletePermitted">
                                    <a class="dropdown-item" @click="deletting(r.id,i)" style="float: right;text-align:right" href="#">حذف نقش</a>
                                </li>
                                <li v-if="isPermittedDynamicRead && r.id>getUser.role_id">
                                    <a class="dropdown-item" @click="permission(r.id,r.roleName)" style="float: right;text-align:right" href="#">تعیین دسترسی</a>
                                </li>
                                <li v-if="isUpdatePermitted">
                                    <a class="dropdown-item" @click="editting(r.id,r.roleName,r.isSuper)" style="float: right;text-align:right" href="#">تغییر نقش</a>
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
    name: "roles",
    data()
    {
        return{
            roles:[],
            roleNames:[],
            add:{
                roleName:'',
                isSuper:null
            },
            edit:{
                id:'',
                roleName:'',
                isSuper:null
            },
            deletee:{
                id:''
            },
            i:null,
        }
    },
    computed:
        {
            ...mapGetters(['getDeleteFlag','getUser'])
        },
    watch:
        {
            getDeleteFlag(obj){
                if (obj) {
                    this.roles.splice(this.i,1)
                    this.$store.commit('setDeleteFlag', false);
                }
            }
        },
    methods:{
        async adding() {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات نقش را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام نقش" id="swal-input1" class="swal2-input"><br><br>
                    <input type="checkbox" placeholder="دسترسی به پنل به نقش داده شود." id="swal-input2">
                    <label for="swal-input2">دسترسی به پنل به نقش داده شود.</label>`,
                focusConfirm: false,
                preConfirm: async () => {

                    if (this.roleNames.includes(document.getElementById('swal-input1').value) ) return this.bottomNotice('این نقش در پنل وجود دارد.','warning')
                    this.add.roleName = document.getElementById('swal-input1').value;
                    this.add.isSuper = document.getElementById('swal-input2').checked;
                    if (this.add.roleName.trim() === '') return this.topNotice('لطفا نام نقش را وارد کنید.', 'error')

                    const res = await this.callApi('post', 'roles/create_role', this.add)
                    if (res.status === 201) {
                       this.roles.unshift(res.data)
                        return this.topNotice('نقش با موفقیت ثبت شد!', 'success')
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
        async editting(id,roleName,is) {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات نقش را وارد کنید.',
                confirmButtonText: 'تغییر',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام نقش" id="swal-inputn" class="swal2-input" value="${roleName}"><br><br>
                    <input type="checkbox" placeholder="دسترسی به پنل به نقش داده شود." id="swal-inputr" checked="${is}">
                    <label for="swal-inputr">دسترسی به پنل به نقش داده شود.</label>`,
                focusConfirm: false,
                preConfirm: async () => {

                    if (this.roleNames.includes(document.getElementById('swal-inputn').value)) return this.bottomNotice('این نقش در پنل وجود دارد.', 'warning')
                    this.edit.roleName = document.getElementById('swal-inputn').value;
                    this.edit.isSuper = document.getElementById('swal-inputr').checked;
                    this.edit.id = id
                    if (this.edit.roleName.trim() === '') return this.topNotice('لطفا نام نقش را وارد کنید.', 'error')

                    const res = await this.callApi('post', 'roles/edit_role', this.edit)
                    if (res.status === 200) {
                        this.roles.unshift(res.data)
                        return this.topNotice('نقش با موفقیت تغییر یافت!', 'success')
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
        deletting(id,i)
        {
            this.deletee.id=id
            this.deleteAlert('آیا از حذف این نقش اطمینان دارید؟','roles/delete_role',this.deletee,'نقش موردنظر با موفقیت حذف شد!','success')
            this.i=i
        },
        permission(id,name)
        {
            localStorage.setItem('role',name);
            return window.location = `/assignrole/${id}`
        }
    },
    async created(){
        const res = await this.callApi('get','app/get_roles');
        console.log(res.data)
        if(res.status === 200){
            this.roles = res.data;
            for (let i of res.data)
            {
                this.roleNames.push(i.roleName)
            }
            console.log(this.roleNames)
        }else {
            this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!','error');
        }
        this.$store.commit('setRoute','assignrole');
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

<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-4 ">
            <button type="button" class="btn btn-purple _box_shadow my-3 " @click="addUser" v-if="isWritePermitted">
                <i class='bx bxs-user-plus'></i>  افزودن کاربر
            </button>
            <table class="table _box_shadow table-bordered border-white table-hover">
                <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">نام </th>
                    <th scope="col">نشانی ایمیل</th>
                    <th scope="col">نقش</th>
                    <th scope="col">تاریخ ثبت کاربر</th>
                    <th scope="col">بیشتر</th>
                </tr>
                </thead>
                <tbody style="background: transparent">
                <tr  v-for="(user, i) in users" :key="i" v-if="users.length">
                    <td >{{user.id}}</td>
                    <td>{{ user.fullName }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{user.role.roleName}}</td>
                    <td>{{ user.created_at }}</td>
                    <td style=" text-align: center; vertical-align:middle">
                        <div class="dropdown">
                        <i class='bx bx-dots-horizontal '
                           id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
                        <ul class="dropdown-menu" style="background: #F7F6FB;box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);" aria-labelledby="dropdownMenuButton1">
                            <li v-if="isDeletePermitted">
                                <a class="dropdown-item" @click="showDeleteModal(user.id,i)" style="float: right;text-align:right" href="#">حذف کاربر</a>
                            </li>
                            <li v-if="isUpdatePermitted">
                                <a class="dropdown-item" @click="editRole(user.id,user.role)" style="float: right;text-align:right" href="#">تغییر نقش</a>
                            </li>
                            <li v-if="isReadPermitted">
                                <a class="dropdown-item" @click="" style="float: right;text-align:right" href="#">جزئیات</a>
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
    name: "users",
    data() {
        return{
            users:[],
            roles:[],
            deleteModal: false,
            newUser:{
                fullName:'',
                email:'',
                password:'',
                role_id:null,
                i:null
            },
            editUser:{
                id:'',
                role_id:''
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
                if (obj) this.users.splice(this.id,1)
            }
        },
    methods:{
        showDeleteModal(user_id, i) {
            const data={
                id:user_id
            }
            this.deleteAlert('آیا از حذف این کاربر اطمینان دارید؟','users/delete_user',data,'کاربر موردنظر با موفقیت حذف شد!','success')
           this.i=i
        },
        async addUser()
        {
            const {value: formValues} = await this.$swal.fire({
                title:'لطفا اطلاعات کاربر را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام کامل" id="swal-input1" class="swal2-input">
                    <input type="email" placeholder=" نشانی ایمیل" id="swal-input2" class="swal2-input">
                    <input type="password" placeholder="رمز عبور" id="swal-input3" class="swal2-input">
                    <select  name="roles" id="swal-input4" class="swal2-input" placeholder="نقش" >
                        ${this.roles.map(r => `<option value="${r.id}">${r.roleName}</option>`)}
                    </select>`,
                focusConfirm: false,
                preConfirm: async () => {
                    this.newUser.fullName = document.getElementById('swal-input1').value;
                    this.newUser.email = document.getElementById('swal-input2').value;
                    this.newUser.password = document.getElementById('swal-input3').value;
                    this.newUser.role_id = document.getElementById('swal-input4').value;
                    if (this.newUser.fullName.trim() === '') return this.topNotice('لطفا نام کاربر را وارد کنید.', 'error')
                    if (this.newUser.email.trim() === '') return this.topNotice('لطفا ایمیل کاربر را وارد کنید.', 'error')
                    if (this.newUser.password.trim() === '') return this.topNotice('لطفا رمز عبور کاربر را وارد کنید.', 'error')
                    if (this.newUser.role_id.trim() === '') return this.topNotice('لطفا نقش کاربر را وارد کنید.', 'error')
                    const res = await this.callApi('post','users/create_user',this.newUser)
                    if (res.status === 201) {
                        setTimeout(function (){window.location = '/panelusers'},3100);
                        return this.topNotice('کاربر با موفقیت ثبت شد!','success')
                    }
                    else {
                        if (res.status === 422) {
                            for (let i in res.data.errors) {
                                return this.topNotice(res.data.errors[i][0],'error')
                            }
                        } else return this.topNotice('مشکلی پیش آمد مجددا تلاش کنید.', 'error')

                    }}
            });
        },
        async editRole(id,role) {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا نقش کاربر را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<select  name="roles" id="swal-input5" class="swal2-input" placeholder="نقش" >
                    <option value="${role.id}" selected>${role.roleName} </option>
                        ${this.roles.map(r => `<option value="${r.id}">${r.roleName}</option>`)}
                    </select>`,
                focusConfirm: false,
                preConfirm: async () => {
                    this.editUser.role_id = document.getElementById('swal-input5').value;
                    this.editUser.id = id;
                    if (this.editUser.role_id.length == null) return this.topNotice('لطفا نقش کاربر را وارد کنید.', 'error')
                    const res = await this.callApi('post', 'users/edit_user_role', this.editUser)
                    if (res.status === 200) {
                        setTimeout(function () {
                            window.location = '/panelusers'
                        }, 3100);
                        return this.topNotice('کاربر با موفقیت تغییر یافت!', 'success')
                    } else {
                        if (res.status === 422) {
                            for (let i in res.data.errors) {
                                return this.topNotice(res.data.errors[i][0], 'error')
                            }
                        } else return this.topNotice('مشکلی پیش آمد مجددا تلاش کنید.', 'error')

                    }
                }
            });
        }
    },
    async created()
    {
        const[res,resRole] = await Promise.all([
            this.callApi('get', 'app/get_panelusers'),
            this.callApi('get', 'app/get_roles')
        ])
        if (res.status === 200)
        {
            this.users = res.data
        }
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!','error');
        if (resRole.status === 200)
        {
            this.roles = resRole.data
        }
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!','error');
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


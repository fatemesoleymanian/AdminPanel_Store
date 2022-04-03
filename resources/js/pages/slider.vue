<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-4 ">
            <button type="button" class="btn btn-purple _box_shadow my-3 " @click="" v-if="isWritePermitted">
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
export default {
    name: "slider"
}
</script>

<style scoped>

</style>

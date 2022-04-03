<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <Breadcrumb>
            <BreadcrumbItem to="/roles">نقش ها</BreadcrumbItem>
            <BreadcrumbItem> تعیین دسترسی {{ role }}  </BreadcrumbItem>
        </Breadcrumb>
        <div class="container mt-4 ">
            <table class="table _box_shadow table-bordered border-white table-hover">
                <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                <tr>
                    <th>#</th>
                    <th> نام بخش</th>
                    <th> مشاهده</th>
                    <th> تغییر</th>
                    <th> حذف</th>
                    <th> ایجاد</th>
                </tr>
                </thead>
                <tbody style="background: transparent">
                <tr  v-for="( r,i) in permissions" :key="i" v-if="permissions.length">
                    <td >{{ r.permission.id }}</td>
                    <td >{{ r.permission.name }}</td>
                    <td><Checkbox  v-model="r.visiter"></Checkbox></td>
                    <td><Checkbox v-model="r.changer"></Checkbox></td>
                    <td><Checkbox v-model="r.remover" ></Checkbox></td>
                    <td><Checkbox v-model="r.writer "></Checkbox></td>

                </tr>
                </tbody>
            </table>
            <div class="py-3">
                <button type="button" class="btn btn-purple _box_shadow my-3" v-if="isUpdatePermitted" @click="submit">
                      ثبت تغییرات
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "assignRole",
    data()
    {
        return{
            permissions:[],
            role:'',
            role_id:null,

        }
    },
    methods: {
        async submit()
        {
            for(let i of this.permissions)
            {
                i.visiter = (i.visiter) ? 1 : 0 ;
                i.changer = (i.changer) ? 1 : 0 ;
                i.remover = (i.remover) ? 1 : 0 ;
                i.writer = (i.writer) ? 1 : 0 ;
            }

            const res = await this.callApi('post','/permissions/change_permission',
                {data:this.permissions,role:this.role_id});
            if (res.status === 200) return this.topNotice('تغییرات با موفقیت ثبت شد.','success')
            else return this.topNotice('خطایی رخ داد!','error')
        }
        },
    async created()
    {
        const id = parseInt(this.$route.params.id)
        const [permissions] = await Promise.all([
            this.callApi('get',`/permissions/get_role_permissions/${id}`)
            ]
        )
        for(let i of permissions.data)
        {
            i.visiter = Boolean(i.visiter)
            i.changer = Boolean(i.changer)
            i.remover = Boolean(i.remover)
            i.writer = Boolean(i.writer)
            this.role_id = i.role_id ;
        }
       this.permissions = permissions.data
        console.log(this.permissions);
        this.role = localStorage.getItem('role')

    },
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


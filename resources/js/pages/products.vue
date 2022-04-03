<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-4 ">
            <Button @click="drawer = true" type="primary" v-if="isReadPermitted">فیلتر</Button>
            <Select v-model="dataToFilter.sort" prefix="md-funnel" style="width:250px" placeholder="مرتب سازی بر اساس:" >
                <Option v-for="(s,i) in sortList" :value="s.value" :key="i" >{{ s.label }}</Option>
            </Select>
                <button @click="filter" v-if="isReadPermitted">مرتب سازی</button>
            <input type="text" v-model="searchStr"
                   @keyup.enter="suggestion"
                   @keyup="suggestion"
                   placeholder="Search" >
            <button v-if="isWritePermitted" type="button" class="btn btn-purple _box_shadow my-3 " @click="adding">
                <i class='bx bxs-user-plus'></i>  افزودن محصول
            </button>
            <table class="table _box_shadow table-bordered border-white table-hover" v-if="products.length">
                <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>مدل</th>
                    <th>گارانتی</th>
                    <th>وضعیت </th>
                    <th>موجودی </th>
                    <th>دسته بندی </th>
                    <th>تاریخ ایجاد</th>
                    <th>بیشتر</th>
                </tr>
                </thead>
                <tbody style="background: transparent">
                <tr  v-for="(p, i) in products" :key="i" v-if="products.length">
                    <td >{{p.id}}</td>
                    <td>{{ p.name }}</td>
                    <td>{{ p.model }}</td>
                    <td>{{ p.guarantee }}ماه</td>
                    <td>{{ 'p.visible' ? 'منتشر شده' : 'منتشر نشده'}}</td>
                    <td>{{ p.inventory }}</td>
                    <td> <span  v-for="(c, j) in p.category" v-if="p.category.length"><Tag color="cyan">{{c.name}}</Tag></span> </td>
                    <td>{{ p.created_at }}</td>
                    <td style=" text-align: center; vertical-align:middle">
                        <div class="dropdown">
                            <i class='bx bx-dots-horizontal '
                               id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" style="background: #F7F6FB;box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);" aria-labelledby="dropdownMenuButton1">
                                <li v-if="isDeletePermitted">
                                    <a class="dropdown-item" @click="deletting(p,i)" style="float: right;text-align:right" href="#">حذف محصول</a>
                                </li>
                                <li v-if="isUpdatePermitted">
                                    <a class="dropdown-item" @click="$router.push(`/products/edit${p.id}`)" style="float: right;text-align:right" href="#">تغییر محصول</a>
                                </li>
                                <li v-if="isReadPermitted">
                                    <a class="dropdown-item" @click="$router.push(`/products/details${p.id}`)" style="float: right;text-align:right"
                                       href="#">جزئیات محصول</a>
                                </li>
                            </ul>
                        </div>
                    </td>

                </tr>
                </tbody>
                <br><br>
                <Page :total="ppRes.total" :current="ppRes.current_page" :page-size="ppRes.per_page" @on-change="productPagination" v-if="ppRes" />
                <Page :total="pfRes.total" :current="pfRes.current_page" :page-size="pfRes.per_page" @on-change="filterPagination" v-else />
             </table>
            <div v-else> <h2>هیچ محصولی یافت نشد!</h2></div>

        </div>
        <Drawer
            placement="bottom"
            title="فیلتر محصولات"
            v-model="drawer"
            width="720"
            height="720"
            :mask-closable="false"
            :styles="styles"
        >
            <Form :model="dataToFilter">
                <Row :gutter="32">
                    <Col span="12">
                        <FormItem label="دسته بندی" label-position="top">
                            <Select v-model="dataToFilter.category" placeholder="please select an owner" filterable multiple>
                                <Option v-for="(c, i) in categories" :value="c.id" :key="i"  :label="c.name">
                                    {{c.name}}
                                </Option>
                            </Select>
                        </FormItem>
                    </Col>
                    <Col span="12">
                        <FormItem label="رنگ" label-position="top">
                            <Select v-model="dataToFilter.color" placeholder="please select an owner" filterable multiple>
                                <Option v-for="(c, i) in colors" :value="c.id" :key="i"  :label="c.name">
                                    {{c.name}}
                                </Option>
                            </Select>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col span="12">
                        <FormItem label="برند" label-position="top">
                            <Select v-model="dataToFilter.brand" placeholder="please select an owner" filterable multiple>
                                <Option v-for="(b, i) in brands" :value="b.id" :key="i">{{ b.company }}</Option>
                            </Select>
                        </FormItem>
                    </Col>
                    <Col span="12">
                        <FormItem label="محدوده قیمت" label-position="top">
                            <Slider v-model="dataToFilter.price" min="0" max="100000000"  range="true"></Slider>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col span="8">
                        <FormItem label="گارانتی" label-position="top">
                            <h5>فقط کالا های گارانتی دار</h5>
                            <i-switch v-model="dataToFilter.guarantee" true-color="#13ce66" false-color="#ff4949" size="large" >
                            </i-switch>
                        </FormItem>
                    </Col>
                    <Col span="8">
                        <FormItem label="کالاهای تخفیف دار" label-position="top">
                            <h5>فقط کالاهای تخفیف دار</h5>
                            <i-switch v-model="dataToFilter.off" true-color="#13ce66" false-color="#ff4949" size="large" >
                            </i-switch>
                          </FormItem>
                    </Col>
                    <Col span="8">
                            <FormItem label="کالاهای مناسبتی" label-position="top">
                                <h5>فقط کالاهای مناسبتی</h5>
                                <i-switch v-model="dataToFilter.special" true-color="#13ce66" false-color="#ff4949" size="large" >
                                </i-switch>
                        </FormItem>
                    </Col>
                </Row>
            </Form>
            <div class="demo-drawer-footer">
                <Button style="margin-right: 8px" @click="drawer = false">Cancel</Button>
                <Button type="primary" @click="filter">Submit</Button>
            </div>
        </Drawer>
    </div>
</template>

<script>
export default {
    name: "products",
    data(){
        return{
            searchStr:'',
            tmp:[],
            products:[],
            categories:[],
            brands:[],
            colors:[],
            i:-1,
            delData:{
                id:null
            },
            img:[],
            dataToFilter:{
                brand:[],
                color:[],
                category:[],
                inventory:true,
                model:null,
                guarantee:true,
                sort:'id',//likes //views //sales //amount //id
                direction:'desc', //asc
                price:[0,10000000],
                off:false,
                special:false
            },
            sortList: [
                {
                    value: 'likes',
                    label: 'محبوب ترین'
                },
                {
                    value: 'views',
                    label: 'پر بازدید ترین'
                },
                {
                    value: 'sales',
                    label: 'پر فروش ترین'
                },
                {
                    value: 'desc',
                    label: 'گران ترین'
                },
                {
                    value: 'asc',
                    label: 'ارزان ترین'
                },
                {
                    value: 'id',
                    label: 'جدید ترین'
                }
            ],
            drawer: false,
            styles: {
                float: 'right',
                text_align:'right',
                position: 'static'
            },
            ppRes:null,
            pfRes:null,
        }
    },
    methods:{
        async filter(){
            if (this.dataToFilter.sort === 'asc')
            {
                this.dataToFilter.sort = 'amount'
                this.dataToFilter.direction = 'asc'
            }
            else {
                this.dataToFilter.sort = 'amount'
                this.dataToFilter.direction = 'desc'
            }
            const res = await this.callApi('post','products/filter',this.dataToFilter)
            this.products.length = 0
            this.ppRes = {}
            if (res.status===200) this.drawer=false
            this.filterPagination(1);
            // if (res.status === 200)
            // {
            //     // this.pfRes =res.data.data;
            //     // this.products = res.data.data;
            //     this.drawer = false;
            // }
        },
        async filterPagination(page) {
            if (typeof page === "undefined") {
                page = 1;
            }
            const r = await this.callApi('get',`http://localhost:8000/products/filter?page=${page}`)
                this.products = r.data.data;
                this.tmp = r.data.data;
                this.pfRes = r.data;
                console.log(r)
        },
        async productPagination(page) {
            if (typeof page === "undefined") {
                page = 1;
            }
            const r = await this.callApi('get',`http://localhost:8000/products/get_products?page=${page}`)
            this.products = r.data.data
            this.tmp = r.data.data;
            this.ppRes = r.data;
            console.log(this.ppRes)
        },
        suggestion()
        {
            if (this.searchStr)
            {
                this.products=this.products.filter((name) => name.name.toLowerCase().includes(this.searchStr.toLowerCase()))
            }
            else {
                this.products = this.tmp
            }
        },
         adding()
        {
            return window.location = '/products/create'
        },
        async deletting(product,index)
        {
            this.delData.id = product.id
            this.$swal.fire({
                title: 'آیا از حذف این محصول اطمینان دارید؟',
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
                    const images = await this.callApi('get', `products/get_images/${product.id}`);
                    for(let i in images.data)
                    {
                         this.img.push(images.data[i].imageUrl);
                    }
                    const d={
                        imageName : this.img
                    }
                    const res = await this.callApi('post', 'products/delete_product', this.delData);
                    if (res.status === 200) {
                        await this.callApi('post', 'app/delete_images', d)
                        this.products.splice(index,1);
                    }
                    if (res.status === 422) {
                        for (let i in res.data.errors) {
                            return this.topNotice(res.data.errors[i][0], 'error')
                        }
                    }
                    else this.topNotice('عملیات ناموفق!', 'error')
                }
            });
        }
    },
    async created() {
        this.productPagination(1);

    },
    async mounted()
    {
        const [brand, category, color] = await Promise.all([
            this.callApi('get', '/brands/get_brands'),
            this.callApi('get', 'categories/get_categories'),
            this.callApi('get', 'colors/get_colors')
        ])
        if (brand.status === 200) this.brands = brand.data
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
        if (category.status === 200) this.categories = category.data
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
        if (color.status === 200) this.colors = color.data
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
    }
}
</script>

<style scoped>
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
.demo-drawer-footer{
    width: 100%;
    position: absolute;
    bottom: 0;
    left: 0;
    border-top: 1px solid #e8e8e8;
    padding: 10px 16px;
    text-align: right;
    background: #fff;
}
</style>

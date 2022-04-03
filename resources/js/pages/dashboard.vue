<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-5 p-2 ">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 col-sm-12 my-sm-2 ">
                    <div class="box text-center border-round bg-gray p-4 _box_shadow ">
                    <h1>12000</h1>
                        <hr style="background: #4723D9">
                    <h4>تعداد کاربران فروشگاه</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 my-sm-2">
                    <div class="box text-center border-round bg-gray p-4 _box_shadow">
                    <h1>{{products}}</h1>
                        <hr style="background: #4723D9">
                    <h4>تعداد محصولات فروشگاه</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 my-sm-2">
                    <div class="box text-center border-round bg-gray p-4 _box_shadow">
                    <h1>200,000,000</h1>
                        <hr style="background: #4723D9">
                    <h4>موجودی حساب فروشگاه</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5 p-2 ">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <div class="box border-round bg-gray p-4 _box_shadow text-right">
                        <h3 class="fw-bold">برند ها</h3>
                        <hr style="background: #4723D9">
                        <div class="container mt-4 ">
                            <input type="text" v-model="brandSearchStr"
                                   @keyup.enter="brandSuggestion"
                                   @keyup="brandSuggestion"
                                   placeholder="Search" >
                            <button type="button" class="btn btn-purple _box_shadow my-3 justify-content-start" @click="addBrand">
                                <i class='bx bxs-user-plus'></i>  افزودن برند
                            </button>
                            <table class="table _box_shadow table-bordered border-white table-hover">
                                <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">کمپانی</th>
                                    <th scope="col">کشور</th>
                                    <th scope="col">لوگو</th>
                                    <th scope="col">تاریخ ایجاد</th>
                                    <th scope="col">بیشتر</th>
                                </tr>
                                </thead>
                                <tbody style="background: transparent">
                                <tr  v-for="(b, i) in brands" :key="i" v-if="brands.length">
                                    <td >{{b.id}}</td>
                                    <td>{{ b.company }}</td>
                                    <td>{{b.country}}</td>
                                    <td><img title="برای مشاهده لوگو کلیلک کنید." :src="b.image" :alt="b.company" width="40px" height="40px" @click="magnifyImage(b)" style="cursor: pointer"/></td>
                                    <td>{{ b.created_at }}</td>
                                    <td width="30px" style=" text-align: center; vertical-align:middle">
                                        <div class="dropdown">
                                            <i class='bx bx-dots-horizontal '
                                               id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                            <ul class="dropdown-menu" style="background: #F7F6FB;box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" @click="deleteBrand(b,i)" style="float: right;text-align:right" href="#">حذف برند</a></li>
                                                <li><a class="dropdown-item" @click="editBrand(b,i)" style="float: right;text-align:right" href="#">برند نقش</a></li>
                                                <li><a class="dropdown-item" @click="" style="float: right;text-align:right" href="#">جزئیات</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container mt-5 p-2 ">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <div class="box border-round bg-gray p-4 _box_shadow text-right">
                        <h3 class="fw-bold">رنگ ها</h3>
                        <hr style="background: #4723D9">
                        <div class="container mt-4 ">
                            <div class="container">
                                <input type="text" v-model="colorSearchStr"
                                       @keyup.enter="colorSuggestion"
                                       @keyup="colorSuggestion"
                                       placeholder="Search" >
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-6" v-for="(c, i) in colors" :key="i" v-if="colors.length" >
                                        <div class="square-color"  :data-tooltip="c.name" data-flow="right" :style="{'background':c.code}" >
                                            <div class="overlay" >
                                                <a  @click="editColor(c,i)"> <i class='bx bx-trash-alt'></i>تغییر رنگ</a>
                                                <a  @click="deleteColor(c,i)">  <i class='bx bx-edit'></i>حذف رنگ</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 border-dark">
                                        <div class="square-color" data-tooltip="برای افزودن رنگ کلیک کنید." data-flow="right" style="border: 1px solid #AFA5D9" @click="addColor">
                                            <i class='bx bx-plus-medical' style="padding: 40% 38%;color:#AFA5D9;font-size: 25px"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container mt-5 p-2 ">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <div class="box border-round bg-gray p-4 _box_shadow text-right ">
                        <h3 class="fw-bold">دسته بندی ها</h3>
                        <hr style="background: #4723D9">
                        <input type="text" v-model="categorySearchStr"
                               @keyup.enter="categorySuggestion"
                               @keyup="categorySuggestion"
                               placeholder="Search" >
                        <button type="button" class="btn btn-purple _box_shadow my-3 justify-content-start" @click="addCategory">
                            <i class='bx bxs-user-plus'></i>  افزودن دسته بندی
                        </button>

                        <table class="table _box_shadow table-bordered border-white table-hover">
                            <thead style="background: #4723D9;color: #F7F6FB;border-color:#4723D9 " >
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">نام دسته بندی</th>
                                <th scope="col">آیکون دسته بندی</th>
                                <th scope="col">تاریخ ایجاد</th>
                                <th scope="col">بیشتر</th>
                            </tr>
                            </thead>
                            <tbody style="background: transparent">
                            <tr  v-for="(c, i) in categories" :key="i" v-if="categories.length">
                                <td >{{c.id}}</td>
                                <td>{{ c.name }}</td>
                                <td><img title="برای مشاهده لوگو کلیلک کنید." :src="c.image" :alt="c.name"
                                         width="40px" height="40px" @click="magnifyImage(c)" style="cursor: pointer"/></td>
                                <td>{{ c.updated_at }}</td>
                                <td width="30px" style=" text-align: center; vertical-align:middle">
                                    <div class="dropdown">
                                        <i class='bx bx-dots-horizontal'
                                           id="dropdownMenuButtonCategory" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu" style="background: #F7F6FB;box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);" aria-labelledby="dropdownMenuButtonCategory">
                                            <li><a class="dropdown-item" @click="deleteCategory(c,i)" style="float: right;text-align:right" href="#">حذف آیتم دسته بندی</a></li>
                                            <li><a class="dropdown-item" @click="editCategory(c,i,c.sub_category)" style="float: right;text-align:right" href="#">تغییر آیتم دسته بندی</a></li>
                                            <li><a class="dropdown-item" @click="" style="float: right;text-align:right" href="#">جزئیات</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "dashboard",
    data()
    {
        return{
            products:null,
            brandSearchStr:'',
            colorSearchStr:'',
            categorySearchStr:'',
            arr:[],
            permissionToDelete:false,
            i:null,
            brands:[],
            tmpBrands:[],
            tmpCategories:[],
            tmpColors:[],
            categories:[],
            colors:[],
            header:{
                headers: {"X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content}
            },
            addingBrand:{
                company:'',
                country:'',
                img:''
            },
            edittingBrand:{
                id:null,
                company:'',
                country:'',
                img:''
            },
            deleteingBrand:{
                id:null
            },
            addingCategory:{
                name:'',
                image:'',
                category_id:null
            },
            delettingCategory:{
              id  :null
            },
            edittingCategory:{
                id:null,
                name:'',
                image:'',
                category_id:null
            },
            addingColor:{
                cname:'',
                code:''
            },
            edittingColor:{
                id:null,
                name:'',
                code:''
            },
            delettingColor:{
                id:null
            },
        }
    },
    computed:
        {
            ...mapGetters(['getDeleteFlag'])
        },
    watch:
        {
            getDeleteFlag(obj){
                console.log('watch')
                if (obj) {
                    this.permissionToDelete = true;
                    this.arr.splice(this.i,1);
                    this.$store.commit('setDeleteFlag', false);
                }
            }
        },
    methods:{
        brandSuggestion()
        {
            if (this.brandSearchStr)
            {
                this.brands=this.brands.filter((name) => name.company.toLowerCase().includes(this.brandSearchStr.toLowerCase()))
            }
            else {
                this.brands = this.tmpBrands
            }
        },
        colorSuggestion()
        {
            if (this.colorSearchStr)
            {
                this.colors=this.colors.filter((name) => name.name.toLowerCase().includes(this.colorSearchStr.toLowerCase()))
            }
            else {
                this.colors = this.tmpColors
            }
        },
        categorySuggestion()
        {
            if (this.categorySearchStr)
            {
                this.categories=this.categories.filter((name) => name.name.toLowerCase().includes(this.categorySearchStr.toLowerCase()))
            }
            else {
                this.categories = this.tmpCategories
            }
        },
        magnifyImage(a)
        {
                this.$swal.fire({
                    imageUrl: a.image,
                    imageAlt: a.company
                })
        },
        async addBrand()
        {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات برند را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="کمپانی " id="swal-inputc" class="swal2-input">
                    <input type="url" placeholder="کشور " id="swal-inputcc" class="swal2-input">
                    <br><br>
                    <input type="file" placeholder="لوگو" id="swal-inputi" class="form-control"
                    accept=".png, .jpg, .jpeg, .svg" />
                  `,
                focusConfirm: false,

                preConfirm: async () => {
                    const file =document.getElementById('swal-inputi').files[0];
                    if (file) {
                        let image = new FormData();
                        image.append('file', file);
                        const resUpload =await this.callApi('post','brand/upload',image)
                        if (resUpload.status === 200) this.addingBrand.img= `/images/brands/${resUpload.data}`
                        else return this.topNotice('خطا در آپلود عکس!','error')
                    }
                    this.addingBrand.company = document.getElementById('swal-inputc').value;
                    this.addingBrand.country = document.getElementById('swal-inputcc').value;

                    if (this.addingBrand.country.trim() === '') return this.topNotice('لطفا کشور را وارد کنید.', 'error')
                    if (this.addingBrand.company.trim() === '') return this.topNotice('لطفا کمپانی را وارد کنید.', 'error')
                    if (this.addingBrand.img.trim() === '') return this.topNotice('لطفا لوگو را انتخاب کنید.', 'error')


                    const res = await this.callApi('post', 'brands/create_brand', this.addingBrand,this.header)
                    if (res.status === 201) {
                        this.brands.unshift(res.data)
                        return this.topNotice('برند با موفقیت ثبت شد!', 'success')
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
        async editBrand(brand,i)
        {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات برند را برای ویرایش وارد کنید.',
                confirmButtonText: 'ثبت تغییرات',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="کمپانی " id="swal-inputc" class="swal2-input" value="${brand.company}">
                    <input type="url" placeholder="کشور " id="swal-inputcc" class="swal2-input" value="${brand.country}">
                    <br>  <img src="${brand.image}" height="80px" width="80px" id="swal2-img" style="cursor: pointer"/><br>
                    <input type="file" placeholder="لوگو" id="swal-inputi" class="form-control"
                    accept=".png, .jpg, .jpeg, .svg" />
                  `,
                focusConfirm: false,

                preConfirm: async () => {
                    this.edittingBrand.id=brand.id
                    this.edittingBrand.company = document.getElementById('swal-inputc').value;
                    this.edittingBrand.country= document.getElementById('swal-inputcc').value;

                    if (this.edittingBrand.company.trim() === '') return this.topNotice('لطفا نام تبلیغ را وارد کنید.', 'error')
                    if (this.edittingBrand.country.trim() === '') return this.topNotice('لطفا لینک تبلیغ را وارد کنید.', 'error')

                    const file =document.getElementById('swal-inputi').files[0];
                    if (file) {
                        let image = new FormData();
                        image.append('file', file);
                        const resUpload =await this.callApi('post','brand/upload',image)
                        if (resUpload.status === 200) {
                            this.edittingBrand.img= `/images/brands/${resUpload.data}`;
                            const imgDel ={imageName:brand.image}
                            const resDelete =await this.callApi('post','app/delete_image',imgDel,this.header)
                            document.getElementById('swal2-img').source=resDelete.data
                        }
                        else {
                            this.topNotice('عکس جدیدی انتخاب نشد!','warning');
                            this.edittingBrand.img = brand.image;
                        }
                    }
                    else {
                        this.edittingBrand.img = brand.image;
                        this.topNotice('عکس جدیدی انتخاب نشد!','warning');
                    }
                    if (this.edittingBrand.img.trim() === '') return this.topNotice('لطفا لوگو را وارد کنید.', 'error')


                    const res = await this.callApi('post', 'brands/edit_brand', this.edittingBrand,this.header)
                    if (res.status === 200)
                    {
                        this.brands[i].image = this.edittingBrand.img;
                        this.brands[i].company = this.edittingBrand.company;
                        this.brands[i].country = this.edittingBrand.country;

                        return this.topNotice('برند با موفقیت تغییر یافت!', 'success');
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
        async deleteBrand(brand, i) {
            this.deleteingBrand.id = brand.id
            this.arr = this.brands
            const img = {imageName: brand.image}
            this.i = i
            this.deleteAlert('آیا از حذف این برند اطمینان دارید؟', 'brands/delete_brand', this.deleteingBrand,
                'برند موردنظر با موفقیت حذف شد!', 'success',img,this.header,true);
        },
        async addColor()
        {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات رنگ را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام رنگ " id="swal-inputn" class="swal2-input">
                    <input type="color" class="form-control form-control-color swal2-input"
                    style="width:80px;margin:15px auto;padding: 0;border:none"
                    placeholder="کد رنگ " id="swal-inputc" title="برای انتخاب رنگ کلیلک کنید.">`,
                focusConfirm: false,

                preConfirm: async () => {
                    this.addingColor.cname = document.getElementById('swal-inputn').value;
                    this.addingColor.code = document.getElementById('swal-inputc').value;

                    if (this.addingColor.cname.trim() === '') return this.topNotice('لطفا نام رنگ را وارد کنید.', 'error')
                    if (this.addingColor.code.trim() === '') return this.topNotice('لطفا کد رنگ را وارد کنید.', 'error')

                    const res = await this.callApi('post', 'colors/create_color', this.addingColor)
                    if (res === undefined) return this.bottomNotice('این رنگ تکراری است!','error')
                    if (res.status === 201) {
                        this.colors.unshift(res.data)
                        return this.topNotice('رنگ محصول با موفقیت ثبت شد!', 'success')
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
        async editColor(color,i)
        {
            const {value: formValues} = await this.$swal.fire({
                title: 'لطفا اطلاعات رنگ را برای ویرایش وارد کنید.',
                confirmButtonText: 'ثبت تغییرات',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام رنگ " id="swal-inputn" class="swal2-input" value="${color.name}" />
                    <input type="color" value="${color.code}" class="form-control form-control-color swal2-input"
                    style="width:80px;margin:15px auto;padding: 0;border:none"
                    placeholder="کد رنگ " id="swal-inputc" title="برای انتخاب رنگ کلیلک کنید." />`,
                focusConfirm: false,

                preConfirm: async () => {
                    this.edittingColor.id = color.id
                    this.edittingColor.name = document.getElementById('swal-inputn').value;
                    this.edittingColor.code = document.getElementById('swal-inputc').value;

                    if (this.edittingColor.name.trim() === '') return this.topNotice('لطفا نام رنگ را وارد کنید.', 'error')
                    if (this.edittingColor.code.trim() === '') return this.topNotice('لطفا کد رنگ را وارد کنید.', 'error')

                    const res = await this.callApi('post', 'colors/edit_color', this.edittingColor)
                    if (res.status === 200)
                    {
                        this.colors[i].name = this.edittingColor.name;
                        this.colors[i].code = this.edittingColor.code;

                        return this.topNotice('رنگ با موفقیت تغییر یافت!', 'success');
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
        async deleteColor(color,i)
        {
            this.delettingColor.id = color.id;
            this.arr = this.colors;
            this.deleteAlert('آیا از حذف این رنگ اطمینان دارید؟', 'colors/delete_color', this.delettingColor, 'رنگ موردنظر با موفقیت حذف شد!', 'success');

            if (this.permissionToDelete) {
                this.i = i
            }
        },
        async addCategory()
        {
            const inputOptions = new Map
            this.categories.forEach(item => inputOptions.set(item.id, item.name))
            const {value: category} = await this.$swal.fire({
                title: 'لطفا اطلاعات دسته بندی را وارد کنید.',
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام " id="swal-inputn" class="swal2-input">
                    <br><br>
                    <input type="file" placeholder="آیکون" id="swal-inputi" class="form-control"
                    accept=".png, .jpg, .jpeg, .svg" />
                  `,
                input: 'select',
                inputOptions,
                inputPlaceholder: 'دسته بندی اصلی',
                focusConfirm: false,
                preConfirm: async () => {

                    const file =document.getElementById('swal-inputi').files[0];
                    if (file) {
                        let image = new FormData();
                        image.append('file', file);
                        const resUpload =await this.callApi('post','category/upload',image)
                        if (resUpload.status === 200) this.addingCategory.image= `/images/categories/${resUpload.data}`
                        else return this.topNotice('خطا در آپلود عکس!','error')
                    }
                    this.addingCategory.name = document.getElementById('swal-inputn').value;

                    if (this.addingCategory.name.trim() === '') return this.topNotice('لطفا نام دسته بندی را وارد کنید.', 'error')
                }
            });
             if (this.addingCategory.name)
             {
                 if (category){
                     this.addingCategory.category_id = category
                 }
                 const res = await this.callApi('post', 'categories/create_category', this.addingCategory)
                 if (res.status === 201) {
                     this.categories.unshift(res.data)
                      this.topNotice('دسته بندی با موفقیت ثبت شد!', 'success')
                     setTimeout(function (){window.location = '/dashboard'},3100);
                 } else {
                     if (res.status === 422) {
                         for (let i in res.data.errors) {
                             return this.topNotice(res.data.errors[i][0], 'error')
                         }
                     } else return this.topNotice('مشکلی پیش آمد مجددا تلاش کنید.', 'error')
                 }
             }
        },
        async editCategory(category,i,sub)
        {
            const inputOptions = new Map
            this.categories.forEach(item => inputOptions.set(item.id, item.name))
            const {value: cat} = await this.$swal.fire({
                title: 'لطفا اطلاعات دسته بندی را برای ویرایش وارد کنید.',
                confirmButtonText: 'ثبت تغییرات',
                cancelButtonText: 'لغو',
                showCancelButton: true,
                showCloseButton: true,
                html:
                    `<input type="text" placeholder="نام دسته بندی " id="swal-inputc" class="swal2-input" value="${category.name}">
                    <br>  <img src="${category.image}" height="80px" width="80px" id="swal2-img" style="cursor: pointer"/><br>
                    <input type="file" placeholder="آیکون" id="swal-inputi" class="form-control"
                    accept=".png, .jpg, .jpeg, .svg" />
                    <select  name="categories" id="swal-inputs" class="swal2-input" placeholder="دسته بندی اصلی" >
                    <option value="${category.id}" selected>${category.sub_category ? category.sub_category.name : 'دسته بندی اصلی'} </option>
                        ${this.categories.map(c => `<option value="${c.id}">${c.name}</option>`)}
                    </select>
                  `,
                focusConfirm: false,
                preConfirm: async () => {
                    this.edittingCategory.id = category.id
                    this.edittingCategory.name = document.getElementById('swal-inputc').value;
                    this.edittingCategory.category_id = document.getElementById('swal-inputs').value;

                    if (this.edittingCategory.name.trim() === '') return this.topNotice('لطفا نام دسته بندی را وارد کنید.', 'error');
                    if (this.edittingCategory.category_id.trim() === '') return this.topNotice('لطفا دسته بندی اصلی را وارد کنید.', 'error');
                        const file = document.getElementById('swal-inputi').files[0];

                    if (file) {
                        let image = new FormData();
                        image.append('file', file);
                        const resUpload =await this.callApi('post','category/upload',image)
                        if (resUpload.status === 200) {
                            this.edittingCategory.image = `/images/categories/${resUpload.data}`;
                            const imgDel ={imageName:category.image}
                            const resDelete =await this.callApi('post','app/delete_image',imgDel,this.header)
                            document.getElementById('swal2-img').source=resDelete.data
                        }
                        else {
                            this.topNotice('عکس جدیدی انتخاب نشد!','warning');
                            this.edittingCategory.image = category.image;
                        }
                    }
                    else {
                        this.edittingCategory.image = category.image;
                        this.topNotice('عکس جدیدی انتخاب نشد!','warning');
                    }

                    const res = await this.callApi('post', 'categories/edit_category', this.edittingCategory,
                        this.header)
                    if (res.status === 200)
                    {
                        this.categories[i].name = this.edittingCategory.name
                        this.categories[i].image = this.edittingCategory.image
                        this.categories[i].sub_category.name = this.edittingCategory.category_id

                        return this.topNotice('دسته بندی با موفقیت تغییر یافت!', 'success');
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
        async deleteCategory(category,i)
        {
            this.delettingCategory.id = category.id
            this.arr = this.categories
            const img = {imageName: category.image}
            this.i = i
             this.deleteAlert('آیا از حذف این دسته بندی اطمینان دارید؟ در صورت وجود زیر دسته بندی های این آیتم نیز حذف میشوند.',
                'categories/delete_category', this.delettingCategory, 'دسته بندی موردنظر با موفقیت حذف شد!',
                'success',img,this.header,true);

        }
    },
    async created() {
        const [brand,category,color,product] = await Promise.all([
            this.callApi('get', '/brands/get_brands'),
            this.callApi('get', 'categories/get_categories'),
            this.callApi('get', 'colors/get_colors'),
            this.callApi('get', 'products/get_numbers'),

        ])
        if(brand.status === 200) {
            this.brands = brand.data;
            this.tmpBrands = brand.data;
        }
        if (category.status === 200){
            this.categories = category.data;
            this.tmpCategories = category.data;
        }
        if(color.status === 200)
        {
            this.colors = color.data;
            this.tmpColors = color.data;
        }
        if (product.status === 200) this.products = product.data
    }

}
</script>

<style scoped>
*{
    direction: rtl;
}
.height-100 {
    min-height: 100vh;
}
.border-round{
    border-radius:10px;
}
.bg-gray{
    background: #f3f3f3;
}
._box_shadow {
    -webkit-box-shadow: 0 10px 20px rgba(71, 35, 217,0.17);
    box-shadow: 0 10px 20px rgba(71, 35, 217,0.17);
}
@media (max-width: 900px)
{
    h1{
        font-size: 1.2rem;
    }
    h4{
        font-size:0.9rem;
    }
}
.dropdown-toggle:after {
    display: none;
}
.dropdown-toggle{
    box-shadow: none;
}
.square-color{
  margin: 20px auto;
    width: 110px;
    height: 110px;
    cursor: pointer;
    border-radius: 50%;
}
[data-tooltip] {
    position: relative;
    cursor: pointer;
}
[data-tooltip]:before,
[data-tooltip]:after {
    line-height: 1;
    font-size: .9em;
    pointer-events: none;
    position: absolute;
    box-sizing: border-box;
    display: none;
    opacity: 0;
    filter: drop-shadow(3px 3px 10px #AFA5D9);
}
[data-tooltip]:before {
    content: "";
    border: 5px solid transparent;
    z-index: 100;
}

[data-tooltip]:after {
    content: attr(data-tooltip);
    text-align: center;
    min-width: 3em;
    max-width: 21em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 10px;
    border-radius: 5px;
    background:#AFA5D9;
    color: #FFFFFF;
    z-index: 99;
}
[data-tooltip]:hover:before,
[data-tooltip]:hover:after {
    display: block;
    opacity: 1;
}
[data-tooltip][data-flow="right"]::before {
    top: 50%;
    border-left-width: 0;
    border-right-color: #AFA5D9;
    right: calc(0em - 5px);
    -webkit-transform: translate(8px, -50%);
    transform: translate(8px, -50%);
}
[data-tooltip][data-flow="right"]::after {
    top: 50%;
    left: calc(100% + 5px);
    -webkit-transform: translate(8px, -50%);
    transform: translate(8px, -50%);
}
[data-tooltip=""]::after, [data-tooltip=""]::before {
    display: none !important;
}
.overlay {
    position: absolute;
    left: 0%;
    top: 0%;
    transform: translate(-50, -50);
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    flex-flow: column wrap;
    justify-content: center;
    align-items: center;
    opacity: 0;
    z-index: 3;
    border-radius: 50%;
    transition: all 0.3s ease;
    overflow-x: hidden;
}
.square-color:hover .overlay
{
    opacity: 1;
}
.square-color:hover .overlay a
{
    font-weight: 600;
    display: block;
    color: #F7F6FB;
    text-decoration: none;
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
@media (max-width: 700px) {
    .square-color{
        width: 60px;
        height: 60px;
    }
}
</style>


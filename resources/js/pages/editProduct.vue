<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <Breadcrumb>
            <BreadcrumbItem to="/products">محصولات</BreadcrumbItem>
            <BreadcrumbItem>تغییر محصول </BreadcrumbItem>
        </Breadcrumb>
        <div class="container mt-4 ">
            <div class="row py-3 ">
                <div class="col-12">نام محصول:
                    <Input v-model="data.name" placeholder="نام محصول:" style="width: 300px;" />
                </div>
            </div>
            <div class="row py-3 bg-light">
                <div class="col-12">مدل محصول:
                    <Input v-model="data.model" placeholder="مدل محصول:" style="width: 300px" />
                </div>
            </div>
            <div class="row py-3 bg-opacity-10">
                <div class="col-12">توضیحات محصول:
                    <Input v-model="data.description" type="textarea" placeholder="توضیحات محصول:" />
                </div>
            </div>
            <div class="row py-3 bg-transparent">
                <div class="col-12">
                    موجودی محصول:
                    <InputNumber  :min="0" :step="1" v-model="data.inventory"></InputNumber>
                </div>
            </div>
            <div class="row py-3 bg-white">
                <div class="col-12">نام برند محصول:
                    <Select v-model="data.brand_id" :placeholder="brandCompany" style="width:200px">
                        <Option v-for="(b, i) in brands" :value="b.id" :key="i">{{ b.company }}</Option>
                    </Select>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    نام دسته بندی:
                    <Select v-model="data.category_id" :placeholder="categoryName"  style="width:260px">
                        <Option v-for="(c, i) in categories" :value="c.id" :key="i">{{ c.name }}</Option>
                    </Select>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    رنگ محصول:
                    <Select v-model="data.color_id" placeholder="رنگ محصول:" filterable multiple style="width:260px">
                        <Option v-for="(c, i) in colors" :value="c.id" :key="i"  :label="c.name">
                            <span>{{ c.name }}</span>
                            <span class="color-options" :style="{'background':c.code}"></span>
                        </Option>
                    </Select>
                </div>
            </div>
            <div class="row p-4">
                <div class="col-12">مدت زمان گارانتی: (ماه)
                    <InputNumber  :min="0" :step="1" v-model="data.guarantee"></InputNumber>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    عکس های محصول :
                    <br><br>
                    <Upload
                        ref="upload"
                        :show-upload-list="true"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        :before-upload="handleBeforeUpload"
                        multiple
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        action="/products/upload"
                        style="display: inline-block;width:58px;">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="ios-camera" size="20"></Icon>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-for="item in  uploadList">
                        <template v-if="item.status === 'finished'">
                            <img :src="item.url">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="magnifyImage(item)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                            </div>
                        </template>
                        <template v-else>
                            <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                        </template>
                    </div>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-12 py-2">
                    قیمت (های) محصول: ( ریال )
                    <br>
                    <Input v-model="data.prices.amount"  placeholder="قیمت اصلی محصول:" class="my-2" style="width: 300px" />
                    <br>
                    <Input v-model="data.prices.special" :disabled="!specialPrice" class="my-2" placeholder="قیمت ویژه محصول:" style="width: 300px" />
                    <Checkbox v-model="specialPrice">افزودن قیمت ویژه</Checkbox>
                    <br>
                    <Input v-model="data.prices.discount"  placeholder="قیمت محصول پس از اعمال تخفیف:" class="my-2" style="width: 300px" :disabled="!offPrice" readonly/>
                    <InputNumber  :min="0" :step="1" v-model="data.prices.off" :disabled="!offPrice" @on-change="doOff"></InputNumber> درصد
                    <Checkbox v-model="offPrice">افزودن قیمت تخفیف</Checkbox>

                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    ویژگی های کوتاه محصول:
                    <Input class="my-2" v-model="data.extraProperty" type="textarea" placeholder="ویژگی های کوتاه محصول:" />
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    <h5>توضیحات اضافی محصول:</h5>
                    <tinymce
                        id="editproduct"
                        apiKey="vhmybrk3gad6ecnpwpnfvfb40l8e6dl9761lvcbxmkr869yv"
                        initialValue="<p>Initial editor content</p>"
                        :init="{
                          height: 1000,
                          menubar: true,
                          plugins: [
                            'advlist autolink lists link image charmap',
                            'searchreplace visualblocks code fullscreen',
                            'print preview anchor insertdatetime media',
                            'paste code help wordcount table'
                          ],
                          toolbar:
                            'undo redo | formatselect | bold italic | \
                            alignleft aligncenter alignright | \
                            bullist numlist outdent indent | help'
                        }"
                        />
                </div>
            </div>
            <div class="row p-4">
                <div class="col-12 py-2">
                    ساعت و تاریخ انتشار محصول در فروشگاه :
                    <br>
                    <datetime-picker v-model="data.publishDate" type="datetime" />
                </div>
                <div class="py-3">
                    <button v-if="isUpdatePermitted" type="button" class="btn btn-purple btn-lg _box_shadow my-3 " large @click="update">
                        <i class='bx bxs-plus-circle'></i>  ثبت تغییرات محصول
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "editProduct",
    data(){
        return{
            token:'',
            brands:[],
            colors:[],
            categories:[],
            data:{
                id:null,
                name:'',
                model:'',
                description:'',
                inventory:0,
                guarantee:0,
                visible:true,
                extraProperty:'',
                details:'',
                publishDate:'',
                brand_id:null,
                color_id:[],
                category_id:null,
                images_id:[],
                prices: {
                    amount:null,
                    off:0,
                    special:0,
                    discount:null,
                },


            },
            specialPrice:false,
            offPrice:false,
            imgName: '',
            visible: false,
            uploadList: [],
            brandCompany:'',
            categoryName:''
        }
    },
    methods:{
        async update()
        {
            this.data.details = tinymce.get("editproduct").getContent();
            const res = await this.callApi('post','/products/edit_product',this.data)
            if (res.status === 200)
            {
                return  this.topNotice('good to go','success')
            }
            if (res.status === 422) {
                for (let i in res.data.errors) {
                    return this.topNotice(res.data.errors[i][0], 'error')
                }
            }
        },
        doOff(e){
            this.data.prices.discount = ((100-this.data.prices.off)*this.data.prices.amount )/100
        },
        magnifyImage(a)
        {
            this.$swal.fire({
                imageUrl: a.url,
                imageAlt: a.name
            })
        },
        handleView (name) {
            this.imgName = name;
            this.visible = true;
        },
        async handleRemove (file) {
            const resDelete =await this.callApi('post','/app/delete_image', { imageName : file.url } ,
                this.header);
            if(resDelete.status === 200) {
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
                this.data.images_id.pop(file.url)
            }
        },
        handleSuccess (res, file) {
            this.data.images_id.push(res.file.url);
            file.url = res.file.url;
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'فرمت فایل اشتباه است.',
                desc: 'فرمت فایل' + file.name + ' اشتباه است لطفا فایل با پسوند .png یا .jpg انتخاب کنید.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'خطا در حجم فایل آپلود شده.',
                desc: 'فایل  ' + file.name + ' حجم بیشتر از 2 مگابایت را دارد.'
            });
        },
        handleBeforeUpload () {
            const check = this.uploadList.length < 5;
            if (!check) {
                this.$Notice.warning({
                    title: 'حداکثر تعداد عکس های محصول 5 عکس است!'
                });
            }
            return check;
        }
    },
    mounted(){
        this.uploadList = this.$refs.upload.fileList;
        this.token =  window.Laravel.csrfToken
    },
    async created() {
        const id = parseInt(this.$route.params.id)
        const [brand, category, color,product,image,price] = await Promise.all([
            this.callApi('get', '/brands/get_brands'),
            this.callApi('get', '/categories/get_categories'),
            this.callApi('get', '/colors/get_colors'),
            this.callApi('get', `/products/get_product/${id}`),
            this.callApi('get', `/products/get_images/${id}`),
            this.callApi('get', `/products/get_prices/${id}`)
        ])
        if (brand.status === 200) this.brands = brand.data
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
        if (category.status === 200) this.categories = category.data
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
        if (color.status === 200) this.colors = color.data
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
        if (product.status === 200)
        {
            this.data.name = product.data.name
            this.data.model = product.data.model
            this.data.details = product.data.details
            this.data.extraProperty = product.data.extraProperty
            this.data.inventory = product.data.inventory
            this.data.guarantee = product.data.guarantee
            this.data.visible = true
            this.data.description = product.data.description
            this.data.publishDate = product.data.updated_at
            this.brandCompany = product.data.brand.company
            tinymce.get("editproduct").setContent(product.data.details);
            this.data.brand_id = product.data.brand_id

            for(let c of product.data.category){
                this.data.category_id = c.id
                this.categoryName = c.name
            }
            for(let c of product.data.color){
                this.data.color_id.push(c.color_id)
            }
            for(let i of image.data){
                this.uploadList.push({ url : i.imageUrl,
                status:'finished'})
                this.data.images_id.push(i.imageUrl)
            }
            console.log( this.uploadList)
                this.data.prices.amount = price.data[0].amount
                this.data.prices.special = price.data[0].special
                this.data.prices.off = price.data[0].off
                this.data.prices.discount = price.data[0].discount
                this.data.id = price.data[0].product_id

        }
        else this.topNotice('مشکلی در ارتباط با سرور پیش آمد. لطفا مجددا تلاش کنید!', 'error');
    }
}
</script>

<style scoped>
.demo-upload-list{
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 4px;
}
.demo-upload-list img{
    width: 100%;
    height: 100%;
}
.demo-upload-list-cover{
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,.6);
}
.demo-upload-list:hover .demo-upload-list-cover{
    display: block;
}
.demo-upload-list-cover i{
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
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
.color-options{
    float:left;
    width: 10px;
    height: 10px;
    opacity: 0.7;
}

</style>

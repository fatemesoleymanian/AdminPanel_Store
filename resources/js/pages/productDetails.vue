<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <Breadcrumb>
            <BreadcrumbItem to="/products">محصولات</BreadcrumbItem>
            <BreadcrumbItem>جزئیات محصول</BreadcrumbItem>
        </Breadcrumb>
        <div class="container mt-4 ">
            <div class="row py-3 ">
                <div class="col-12">نام محصول:
                    <Input v-model="name" placeholder="نام محصول:" style="width: 300px;" />
                </div>
            </div>
            <div class="row py-3 bg-light">
                <div class="col-12">مدل محصول:
                    <Input v-model="model" placeholder="مدل محصول:" style="width: 300px" />
                </div>
            </div>
            <div class="row py-3 bg-opacity-10">
                <div class="col-12">توضیحات محصول:
                    <Input v-model="description" type="textarea" placeholder="توضیحات محصول:" />
                </div>
            </div>
            <div class="row py-3 bg-transparent">
                <div class="col-12">
                    موجودی محصول:
                    <InputNumber readonly v-model="inventory"></InputNumber>
                </div>
            </div>
            <div class="row py-3 bg-white">
                <div class="col-12">نام برند محصول:
                    <Select v-model="brand_id" :placeholder="brandCompany" style="width:200px">
                        <Option v-for="(b, i) in brands" :value="b.id" :key="i">{{ b.company }}</Option>
                    </Select>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    نام دسته بندی:
                    <Select v-model="category_id" :placeholder="categoryName"  style="width:260px">
                        <Option v-for="(c, i) in categories" :value="c.id" :key="i">{{ c.name }}</Option>
                    </Select>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    رنگ محصول:
                    <Select v-model="color_id" placeholder="رنگ محصول:" filterable multiple style="width:260px">
                        <Option v-for="(c, i) in colors" :value="c.id" :key="i"  :label="c.name">
                            <span>{{ c.name }}</span>
                            <span class="color-options" :style="{'background':c.code}"></span>
                        </Option>
                    </Select>
                </div>
            </div>
            <div class="row p-4">
                <div class="col-12">مدت زمان گارانتی: (ماه)
                    <InputNumber  :min="0" :step="1" v-model="guarantee"></InputNumber>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-6" v-for="(c, i) in images_id" :key="i" v-if="images_id.length">
                            <img :src="c" width="90px" height="90px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-12 py-2">
                    قیمت (های) محصول: ( ریال )
                    <br>
                    <Input v-model="prices.amount"  placeholder="قیمت اصلی محصول:" class="my-2" style="width: 300px" />
                    <br>
                    <Input v-model="prices.special" readonly class="my-2" placeholder="قیمت ویژه محصول:" style="width: 300px" />
                    <br>
                    <Input v-model="prices.discount"  placeholder="قیمت محصول پس از اعمال تخفیف:" class="my-2" style="width: 300px" :disabled="!offPrice" readonly/>
                    <InputNumber  :min="0" :step="1" v-model="prices.off" readonly></InputNumber> درصد

                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    ویژگی های کوتاه محصول:
                    <Input class="my-2" v-model="extraProperty" type="textarea" placeholder="ویژگی های کوتاه محصول:" />
                </div>
            </div>
            <div class="row py-3">
                <div class="col-12">
                    <h5>توضیحات اضافی محصول:</h5>
                    <tinymce
                        id="product"
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
                    <datetime-picker v-model="publishDate" type="datetime" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "productDetails",
    data(){
            return{
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
            }
        },
    async created() {
        const id = parseInt(this.$route.params.id)
        const [product,image,price] = await Promise.all([
            this.callApi('get', `/products/get_product/${id}`),
            this.callApi('get', `/products/get_images/${id}`),
            this.callApi('get', `/products/get_prices/${id}`)
        ])
        if (product.status === 200)
        {
            this.name = product.data.name
            this.model = product.data.model
            this.details = product.data.details
            this.extraProperty = product.data.extraProperty
            this.inventory = product.data.inventory
            this.guarantee = product.data.guarantee
            this.visible = true
            this.description = product.data.description
            this.publishDate = product.data.updated_at
            tinymce.get("product").setContent(product.data.details);

            for(let c of product.data.category){
                this.category_id = c.id
                }
            for(let c of product.data.color){
                this.color_id.push(c.color_id)
            }
            for(let i of image.data){
                this.images_id.push(i.imageUrl)
            }
            this.prices.amount = price.data[0].amount
            this.prices.special = price.data[0].special
            this.prices.off = price.data[0].off
            this.prices.discount = price.data[0].discount

        }
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
.color-options{
    float:left;
    width: 10px;
    height: 10px;
    opacity: 0.7;
}
</style>

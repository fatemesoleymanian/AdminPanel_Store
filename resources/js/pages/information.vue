<template>
    <div class="height-100 bg-light _box_shadow _padding">
        <div class="container mt-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="card px-5 py-5" id="form1">
                        <div class="form-data" v-if="!submitted">
                            <div class="forms-inputs mb-4"> <span>نام کاربری</span>
                                <input autocomplete="on" type="text" v-model="data.fullName"
                                       v-bind:class="{'form-control':true, 'is-invalid' : !validFullName(data.fullName) && fullNameBlured}"
                                       v-on:blur="fullNameBlured = true"
                                       @keyup.enter="submit">
                                <div class="invalid-feedback purple_color">لطفا نام کاربری خود را وارد کنید!</div>
                            </div>
                            <div class="forms-inputs mb-4"> <span>ایمیل</span>
                                <input autocomplete="on" type="text" v-model="data.email" @keyup.enter="submit"
                                       v-bind:class="{'form-control':true, 'is-invalid' : !validEmail(data.email) && emailBlured}"
                                       v-on:blur="emailBlured = true">
                                <div class="invalid-feedback purple_color">لطفا ایمیل خود را به درستی وارد کنید!</div>
                            </div>
                            <div class="forms-inputs mb-4"> <span>رمز عبور</span>
                                <input autocomplete="on" type="password" @keyup.enter="submit"
                                       v-model="data.password" v-bind:class="{'form-control':true, 'is-invalid' : !validPassword(data.password) && passwordBlured}" v-on:blur="passwordBlured = true">
                                <div class="invalid-feedback purple_color">رمز عبور باید شامل حداقل 8 کاراکتر باشد!</div>
                            </div>
                            <div class="mb-3">
                                <button v-on:click.stop.prevent="submit" class="btn w-100 purple">ثبت</button>
                            </div>
                        </div>
                        <div class="success-data purple" style="border: none;" v-else>
                            <div class="text-center d-flex flex-column">
                                <i class='bx bxs-badge-check' style="color: #AFA5D9;"></i>
                                <span class="text-center fs-1">اطلاعات شما با موفقیت ثبت شد! </span> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "information",
    data()
    {
        return{
            emailBlured : false,
            valid : false,
            submitted : false,
            passwordBlured:false,
            fullNameBlured:false,
            data:{
                email:'',
                password:'',
                fullName:''
            }
        }
    },
    methods: {
        validate()
        {
            this.emailBlured = true;
            this.passwordBlured = true;
            this.fullNameBlured = true;
            if( this.validEmail(this.data.email) &&
                this.validPassword(this.data.password) && this.validFullName((this.data.fullName)))
                this.valid = true;
        },
        validEmail(email)
        {
            var re = /(.+)@(.+){2,}\.(.+){2,}/;
            if(re.test(email.toLowerCase())) return true;
        },
        validFullName(fullName)
        {
            if(fullName.trim() != '') return true;
        },
        validPassword(password)
        {
            if (password.length > 7) return true;
        },
        async submit()
        {
            this.validate();
            if (this.valid) {
                console.log(this.submitted)
            const res =await this.callApi('post', 'users/update_information', this.data);
                console.log(res)
                if (res.status === 200){
                    console.log(this.submitted)
                    this.topNotice('اطلاعات شما تغییر یافت!','success');
                    setTimeout(function () { window.location = '/'},3100);
                }
        }
        }
    },
    created()
    {
        this.data.email= this.getUser.email
        this.data.fullName= this.getUser.fullName
    },
    computed:
        {
            ...mapGetters(['getUser'])
        }

}
</script>

<style scoped>
.card {
    border: none;
    height: 420px;
    -webkit-box-shadow: 0 10px 20px rgba(175, 165, 217,0.3);
    box-shadow: 0 10px 20px rgba(175, 165, 217, 0.3);
}

.forms-inputs {
    position: relative
}

.forms-inputs span {
    position: absolute;
    top: -18px;
    left: 10px;
    background-color: #fff;
    padding: 5px 10px;
    font-size: 15px
}

.forms-inputs input {
    height: 50px;
    border: 2px solid #eee
}

.forms-inputs input:focus {
    box-shadow: none;
    outline: none;
    border: 2px solid #4723D9;
}

.btn {
    height: 50px
}

.success-data {
    display: flex;
    flex-direction: column
}

.bxs-badge-check {
    font-size: 90px
}
.purple
{
    border:1px solid #AFA5D9;
    transition:all 0.4s ease;
    font-weight: 600;
    -webkit-box-shadow: 0 10px 20px rgba(175, 165, 217, 0.1);
    box-shadow: 0 10px 20px rgba(175, 165, 217, 0.1);
}
.purple:hover{
    background: #AFA5D9;
}
.purple_color{
    color: #4723D9;
}
</style>

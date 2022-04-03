<template>
<div id="body-pd" >
    <header class="header" id="header" v-if="$store.state.user">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="header_img"> <img src="/images/VdentLogo.png" alt="لوگوی ویدنت"> </div>
    </header>
    <div class="l-navbar _box_shadow" id="nav-bar" v-if="$store.state.user">
        <nav class="nav">
            <div>
                <router-link to="/" class="nav_logo">
                    <i class='bx bx-user nav_logo-icon'></i>
                    <span class="nav_logo-name">{{ name }}</span>
                </router-link>
                <div class="nav_list">
                    <router-link v-for="(item,i) in permission" :key="i" v-if="item.permission.icon !== null &&
                    item.visiter && item.permission.route !== '/' "  :to='item.permission.route' class="nav_link ">
                        <i :class='item.permission.icon'></i>
                        <span class="nav_name text-white">{{ item.permission.name }}</span>
                    </router-link>

<!--                    <router-link to="/observeproduct" class="nav_link">-->
<!--                        <i class='bx bxs-file-find nav_icon'></i>-->
<!--                        <span class="nav_name">بررسی محصولات</span>-->
<!--                    </router-link>-->

<!--                    <router-link to="/assignrole" class="nav_link">-->
<!--                        <i class='bx bx-directions nav_icon'></i>-->
<!--                        <span class="nav_name">تعیین دسترسی</span>-->
<!--                    </router-link>-->

                </div>
            </div>
            <a href="/logout" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">خروج</span>
            </a>
        </nav>
    </div>
    <!--Container Main start-->

    <!--Container Main end-->
    <router-view/>
        </div>
</template>

<script>
export default {
    name: "sidebar",
    props:['user','permission'],
    data(){
        return{
            name:'',
        }
    },
    async created()
    {
        this.$store.commit('setUpdateUser',this.user);
        this.name=this.user.fullName;
        this.$store.commit('setUserPermission',this.permission);
        //permission
        // console.log(this.user)
    },
    mounted()
    {
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) =>{
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

// Validate that all variables exist
                if(toggle && nav && bodypd && headerpd){
                    toggle.addEventListener('click', ()=>{
// show navbar
                        nav.classList.toggle('show')
// change icon
                        toggle.classList.toggle('bx-x')
// add padding to body
                        bodypd.classList.toggle('body-pd')
// add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle','nav-bar','body-pd','header')

            /*===== LINK ACTIVE =====*/
            // const linkColor = document.querySelectorAll('.nav_link')
            //
            // function colorLink(){
            //     if(linkColor){
            //         linkColor.forEach(l=> l.classList.remove('active'))
            //         this.classList.add('active')
            //     }
            // }
            // linkColor.forEach(l=> l.addEventListener('click', colorLink))

// Your code to run since DOM is loaded and ready
        });

    }
}
</script>

<style scoped>
*,
::before,
::after {
    box-sizing: border-box;
}

body {
    position: relative;
    margin: 3rem 0 0 0;
    padding: 0 1rem;
    font-family: 'Noto Naskh Arabic', serif;
    font-size: 1rem;
    transition: .5s;
}

a{
    text-decoration: none;
}

.header {
    width: 100%;
    height:3rem;
    position: fixed;
    top: 0;
    right: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    background-color:  #F7F6FB;
    z-index: 100;
    transition: .5s;
}


.header_toggle {
    color:#4723D9;
    font-size: 1.5rem;
    cursor: pointer;
}

.header_img {
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    border-radius: 50%;
    overflow: hidden;
    /*border: 1px solid #AFA5D9;*/
    -webkit-box-shadow: 0 10px 40px #AFA5D9;
    box-shadow: 0 10px 40px #AFA5D9;
}

.header_img img {
    width: 40px;
}

.l-navbar {
    position: fixed;
    top: 0;
    right: -30%;
    width: 68px;
    height: 100vh;
    background-color: #4723D9;
    padding: .5rem 0 0 1rem;
    transition: .5s;
    z-index: 100;
}

.nav {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

.nav_logo,
.nav_link {
    display: grid;
    grid-template-columns: max-content max-content;
    align-items: center;
    column-gap: 1rem;
    padding: .5rem 1.3rem .5rem 0;
    right: 1%;
}

.nav_logo {
    margin-bottom: 2rem;
}

.nav_logo-icon {
    font-size: 1.25rem;
    color:  #F7F6FB;
}

.nav_logo-name {
    color:  #F7F6FB;
    font-weight: 700;
}

.nav_link {
    position: relative;
    color:  #AFA5D9;
    margin-bottom: 1.5rem;
    transition: .3s;
}

.nav_link:hover {
    color: #F7F6FB;
}

.nav_icon {
    font-size: 1.25rem;
}

.l-navbar.show {
    right: 0;
}

.body-pd {
    padding-right: calc(68px + 1rem);
}
.router-link-exact-active{
    color: #F7F6FB!important;
    border-right: 2px solid #F7F6FB !important;
}
.active {
    color: #F7F6FB;
}

.active::before {
    content: '';
    position: absolute;
    right: 0;
    width: 2px;
    height: 32px;
    background-color:  #F7F6FB;
}

.height-100 {
    min-height: 100vh;
    margin: 4.5rem 5rem 0 1rem;
}
._box_shadow {
    -webkit-box-shadow: 0 10px 20px rgba(175, 165, 217, 1);
    box-shadow: 0 10px 20px rgba(175, 165, 217, 1);
}
._padding{
    padding: 40px;
}
@media screen and (min-width: 768px) {
    body {
        margin: calc(3rem + 1rem) 0 0 0;
        padding-right: calc(68px + 2rem);
    }

    .header {
        height: calc(3rem + 1rem);
        padding: 0 calc(68px + 2rem) 0 2rem;
    }

    .header_img {
        width: 40px;
        height: 40px;
    }

    .header_img img {
        width: 45px;
    }

    .l-navbar {
        right: 0;
        padding: 1rem 0 0 1rem;
    }

    .show {
        width: calc(68px + 156px);
    }

    .body-pd {
        padding-right: calc(68px + 188px);
    }
    .body-pd .height-100{
        margin-right:-5px;
    }
}

</style>


<script>

    import axios from 'axios'; 
 
    export default{
        data(){
            return{
                users:{},
                form: {
                    name:"",
                    email:"",
                    password:"",
                },
                editId:"",
                editMode:false,
                userDetail:{},
                searchUser:''
                
                
            }
        },
        methods:{
            // getImage(event)
            // {
            //     console.log(event)
            //     this.form.image=event.target.files[0]
            //     if (this.form.image) {
            //         this.preview = URL.createObjectURL(this.form.image)
            //     } else {
            //         this.preview = null
            //     }
            // },
            async fetchusers(url='/api/users'){
                // const response = await axios.get(url);
                // console.log(response); 
                // this.users = response.data.data;

                // down is other way and up is other way
                const {data} = await axios.get(url);
                this.users = data;
               
                
            },
            async saveuser()
            {
                if(this.form.name==""){
                    alert('empty name')
                    return false
                }
                if(this.form.email==""){
                    alert('empty email')
                    return false
                }
                if(this.form.password==""){
                    alert('empty password')
                    return false
                }
                
                if(this.editMode){
                    await axios.put(`/api/users/${this.editId}`,this.form);
                    this.editId=''
                    this.editMode=false
                }
                else{
                    await axios.post('/api/users',this.form);
                }
                this.form= {
                    name:'',
                    email:'',
                    password:''
                }
                this.fetchusers()
            },
            edituser(user)
            {
                this.form= {
                    name:user.name,
                    email:user.email,
                    password:user.password
                }
                this.editId=user.id
                this.editMode=true
            },
            async deleteuser(id)
            {
                await axios.delete(`/api/users/${id}`);
                 
                this.users.data = this.users.data.filter(user => user.id !== id);
            },
            async searchUsers()
            {
                if(this.searchUser.length>=3)
                {
                    const { data } = await axios.post('/api/search-user', {
                        searchUser: this.searchUser
                    });
                    this.users = data;
                }
                else{
                    this.fetchusers()
                }
                
                 
            },
            // async detailuser(userData)
            // {
            //     this.userDetail = userData
            //     console.log(this.userDetail);
            // },
            async logOut()
            {
                try {
                    const { data } = await axios.get('/api/tlogout');

                    if (data.success) {
                        // 👇 Navigate to User.vue using router
                        this.$router.push('/tshowLogin');
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert(error.response?.data?.message || 'Something went wrong');
                }
            },
            
        }, 
        mounted(){
            this.fetchusers()
        }
    }
</script>
<template>
    <div class="container p-10">
        <div class="d-flex justify-content-between align-items-center">
            <div><h1>users</h1></div>
            <div> 
                <button class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600" @click="logOut">
                Logout
                </button>
            </div>
        </div>
        
        <form action="" class="shadow-md mb-6   p-4 rounded-md" @submit.prevent="saveuser" >
            <div class="mb-4">
                <input type="text" placeholder="name" v-model="form.name" class="w-full p-2 border border-grey-300 rounded-md
                 focus:outline-none focus:ring focus:ring-indigo-300">
            </div>
            <div class="mb-4">
                <input type="email" placeholder="Email" v-model="form.email" class="w-full p-2 border border-grey-300 rounded-md
                 focus:outline-none focus:ring focus:ring-indigo-300">
            </div>
            <div class="mb-4">
                <input type="password" placeholder="Password" v-model="form.password" class="w-full p-2 border border-grey-300 rounded-md
                 focus:outline-none focus:ring focus:ring-indigo-300">
            </div>
           
            <button class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">
                {{ editMode ? 'Update' : 'Create' }}
            </button> 
        </form>
        <!-- <div v-if="users"> -->
            <div class="mb-4 bg-white p-4 shadow-md">
                <input type="text" placeholder="Search User" @keyup="searchUsers" v-model="searchUser" class="w-full p-2 border border-grey-300 rounded-md
                 focus:outline-none focus:ring focus:ring-indigo-300">
            </div>
            <div  v-for="user in users.data" :key="user.id" class="mb-4 bg-white p-4 shadow-md">
                <div class="d-flex justify-between">
                    <h3 class="text-xl font-semibold">{{ user.name }}</h3>
                    <h5 class="text-xl font-semibold">{{ user.email }}</h5>
                </div>
                <!-- <p class="text-grey">{{ user.content }}</p> -->
                <button class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 mt-3"
                @click="edituser(user)">
                    Edit
                </button> 
                <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mt-3 ms-3"
                @click="deleteuser(user.id)">
                    Delete
                </button> 
                <button class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mt-3 ms-3"
                @click="$router.push({
                name:'show-user',
                params:{
                    id:user.id
                }})">
                    Detail
                </button> 

            </div>
          
            <div v-if="users.total>0" class="d-flex justify-center gap-2 items-center space-x-2 mt-6">
                <button v-for="(link,index) in users.links" 
                :key="index"
                :disabled="!link.url"
                @click="fetchusers(link.url)"
                class="px-4 py-2 rounded"
                :class="{
                    'bg-indigo-500 text-white hover:bg-indigo-600': link.active,
                    'bg-dard-grey text-white': !link.active && link.url,
                    'bg-grey text-white cursor-not-allowed': !link.url,
                }"
                v-html="link.label"
                ></button>
            </div>
        <!-- </div> -->
        <br>
    </div>
</template>

<style scoped>
   .bg-grey{
    background-color: lightgrey;
   }
   .bg-dard-grey{
    background-color: darkgrey;
   }
</style>
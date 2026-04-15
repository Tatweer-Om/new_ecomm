<script>
    import axios from 'axios';  

    export default {
        data() {
            return {
                form: {
                    name: "",
                    password: "",
                },
            };
        },
        methods: {
            async tlogin() {
                if (!this.form.name) {
                    alert('empty name');
                    return;
                }
                if (!this.form.password) {
                    alert('empty password');
                    return;
                }

                try {
                    const { data } = await axios.post('/api/tlogin', this.form);

                    if (data.success) {
                        // 👇 Navigate to User.vue using router
                        console.log('loggedin')
                        this.$router.push('/users');
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert(error.response?.data?.message || 'Something went wrong');
                }
            },
        },
    };

</script>

<template>
    <div class="card">
        <h3 class="text-center mb-4">Login</h3> 
        <form action="" method="POST" @submit.prevent="tlogin">
            
        <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control" v-model="form.name" placeholder="Enter name" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" v-model="form.password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn btn-primary rounded-pill mt-3">Login</button>
        </form>
    </div>
</template>

<style scoped></style>
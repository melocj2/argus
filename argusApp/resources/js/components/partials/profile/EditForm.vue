<template>
    
    <div> 

        <div class="icon" :style="{'background': `url(${user.avatar_uri})`}"></div>

        <form ref="avatarForm" enctype="multipart/form-data">
        <input type="hidden" name="upload_avatar" value="1" /> 
            <div>
                <label for="avatar">Avatar *</label> 
                <input type="file" name="avatar" id="avatar" /> 
            </div> 

            <div> 
                <label for="new_name">Name</label> 
                <input type="text" id="new_name" name="new_name" :placeholder="user.name" v-model="newName" /> 
            </div> 
            
            <div> 
                <label for="new_email">Email</label>
                <input type="text" id="new_email" name="new_email" :placeholder="user.email" v-model="newEmail" /> 
            </div> 

            <div> 
            Change Password 
                <button type="button" @click="showPasswords = !showPasswords" > 
                    <template v-if="showPasswords"> 
                        Hide passwords 
                    </template> 
                    
                    <template v-else> 
                        Show passwords 
                    </template> 
                </button> 
            </div> 
        
            <div> 
                <label for="password">Current Password</label> 
                <input :type="showPasswords ? 'text' : 'password'" id="password" name="password" v-model="currentPassword" /> 
            </div> 
            
            <div> 
                <label for="new_password">New Password</label> 
                <input :type="showPasswords ? 'text' : 'password'" id="new_password" name="new_password" v-model="newPassword" /> 
            </div> 
            
            <div> 
                <label for="new_password_confirm">Confirm New Password</label>
                <input :type="showPasswords ? 'text' : 'password'" id="new_password_confirm" name="new_password_confirm"  v-model="confirmPassword" /> 
            </div> 
            
            <button type="button" @click="submit" :disabled="processing"> 
                {{ processing ? 'Processing...' : 'Update' }} 
            </button> 

        </form> 
    </div> 

</template>

<script>
    export default {
        data() {
            return {
                showPasswords: false,
                currentPassword: null,
                newPassword: null,
                confirmPassword: null,
                newName: null,
                newEmail: null,
                errors: null,
                processing: false
            };
        },

        computed: {
            user () {
                return this.$store.state.user;
            }
        },

        methods: {
            submit() {
                this.processing = true;
                this.errors = null;

                const formData = new FormData(this.$refs.avatarForm);
                formData.append('_method', 'PATCH');

                axios.post(`/api/users/${this.user.id}`, formData)
                    .then(response => {
                        this.$store.commit('user', response.data);
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    }).then(() => {
                        this.processing = false;
                    });

                axios.patch(`/api/users/${this.user.id}`, {
                    'new_name' : this.newName,
                    'new_email' : this.newEmail
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    }).then(() => {
                        this.newName = null;
                        this.newEmail = null;
                        this.processing = false;
                    });

                axios.patch(`/api/users/${this.user.id}`, {
                    'old_password' : this.currentPassword,
                    'new_password' : this.newPassword,
                    'new_password_confirmation' : this.confirmPassword
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    }).then(() => {
                        this.currentPassword = null;
                        this.newPassword = null;
                        this.confirmPassword = null;
                        this.processing = false;
                    });
            }
        }
    }
</script>
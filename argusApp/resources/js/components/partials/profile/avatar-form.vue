<template>
    <div class="card">
        <div class="card-header">
            Avatar
        </div>
        <button @click="logSensors">Log Sensor Data</button>
        <div class="card-body">
            <template v-if="hasAvatar">
                <div
                    class="avatar"
                    :style="{'background': `url(${user.avatar_uri})`}"
                >
                </div>
            </template>
            <template v-else>
                <form ref="avatarForm" enctype="multipart/form-data">
                    <input type="hidden" name="upload_avatar" value="1" />
                    <div class="form-group">
                        <label for="avatar">Avatar *</label>
                        <input type="file" class="form-control" name="avatar" id="avatar" />
                    </div>
                    <div v-if="errors" class="alert alert-danger">
                        <p class="alert-heading h4">There were some issues updating your information.</p>
                        <p v-for="(error, key) in errors" class="mb-0" :key="`errors-${key}`">
                            {{ error[0] }}
                        </p>
                    </div>
                    <button class="btn btn-primary btn-block" @click="submit" :disabled="processing">
                        {{ processing ? 'Processing...' : 'Upload' }}
                    </button>
                </form>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                processing: false,
                errors: null
            };
        },

        computed: {
            user() {
                return this.$store.state.user;
            },

            hasAvatar() {
                return this.user
                    ? this.user.has_avatar
                    : false;
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
            },

        logSensors() {

            console.log("IIFE fired!");

            let sensorData = firebase.database().ref('sensors');

             sensorData.on('value', function(snapshot){
                console.log('gas', Math.round(snapshot.val().gas.value));
            });

            sensorData.on('value', function(snapshot){
                console.log(Math.round(snapshot.val().light.value));
            });

            sensorData.on('value', function(snapshot){
                console.log(snapshot.val().temp.value);
            });

            sensorData.on('value', function(snapshot){
                console.log(Math.round(snapshot.val().moisture.value));
            });
        }
    }
}
</script>

<style lang="scss" scoped>
    .avatar {
        background-size:cover !important;
        background-position:center !important;
        height:300px;
        width:100%;
        margin:0 auto;
    }
</style>

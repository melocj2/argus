<template>
    <div>
        <h1>Profile</h1>
        <div class="row">
            <div class="col-md-7">
                <div class="mt-3">
                </div>
            </div>
            <div class="col-md-5">
                <avatar-form />
            </div>
        </div>
    </div>
</template>

<script>
    import AvatarForm from './partials/profile/avatar-form.vue';

    export default {
        components: {
            AvatarForm
        }
    };
</script>

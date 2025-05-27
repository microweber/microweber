<template>
    <div class="mt-2">
        <div>
            <b>{{ setting.title }}</b>
        </div>
        <div class="mb-2">
            <small>{{ setting.description }}</small>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        setting: {
            type: Object,
            required: true
        }
    }
};
</script>

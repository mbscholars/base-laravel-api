<script>

export default {
props: {
    action: { type: String, default: 'Submit' },
    disabled: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
},

  setup(){

    }
}

</script>

<template>

    <button type="submit" @click="$emit('submit')" :class="
    {
        'cursor-not-allowed opacity-50': disabled,
        'cursor-pointer': !disabled,
        'cursor-wait': loading
    }
    "
    class="button"> {{ action }}</button>
</template>

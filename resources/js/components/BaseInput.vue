<script>
export default{
    props: {
        label: { type: String, default: '' },
        value: { type: String, default: '' },
        error: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        type: { type: String, default: 'text' },
        name: { type: String, default: '' },
        id: { type: String, default: '' },
        required: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
        readonly: { type: Boolean, default: false },
        autofocus: { type: Boolean, default: false },
        autocomplete: { type: Boolean, default: false },
        maxlength: { type: Number, default: 255 },
        minlength: { type: Number, default: 0 },
        rows: { type: Number, default: 3 },
        cols: { type: Number, default: 3 },
        step: { type: Number, default: 1 },
        min: { type: Number, default: 0 },
        max: { type: Number, default: 100 },
        pattern: { type: String, default: '' },
        size: { type: String, default: '' },
        accept: { type: String, default: '' },
        multiple: { type: Boolean, default: false },
        modelValue: {
        type: [String, Number],
        default: ''
        }

    },

    setup(props) {

        const hasError = function(name){
            if (props.error[name]) {
                return true;
            }
        }

        return {
            hasError
        }
    },

}
</script>

<template>

<div class="mt-2 form-group">
 	<div class="floating-label-group">
         <input
                :type="type"
                :name="name"
                :id="id"
                :value="modelValue"
                :required="required"
                :disabled="disabled"
                :readonly="readonly"
                :autofocus="autofocus"
                :placeholder="placeholder"
                :autocomplete="autocomplete"
                :maxlength="maxlength"
                :minlength="minlength"
                :rows="rows"
                :cols="cols"
                :step="step"
                :min="min"
                :max="max"
                :pattern="pattern"
                :size="size"
                :accept="accept"
                :multiple="multiple"
                class="form-control block w-full sm:text-sm sm:leading-5  "
                :class="{'border-red-500': !!error}"
                @input="$emit('update:modelValue', $event.target.value)" />

				<label class="floating-label">{{ label  }}</label>
                <div class="mB-2 form-group">
                    <span class="text-red-500 text-xs italic" v-if="hasError(name)">{{ error }}</span>
                </div>
			</div>

        </div>

</template>

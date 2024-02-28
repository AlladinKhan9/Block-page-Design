<template>
  <div class="form-picker tw-py-2">
    <select 
    @change="$emit('updateConfig','newsletter_form', parseInt($event.target.value));"
    v-model="config.newsletter_form">
      <option :value="null" selected disabled>Select a form</option>
      <option v-for="form in forms" :key="form.id" :value="form.id">{{form.title}}</option>
    </select>
    <select
    @change="$emit('updateConfig','newsletter_field', parseInt($event.target.value));"
    v-model="config.newsletter_field"
    v-if="selectedForm">
      <option :value="null" selected disabled>Select a field</option>
      <option v-for="field in selectedForm.fields" :key="field.id" :value="field.id">{{field.label}}</option>
    </select>
  </div>
</template>

<script>
export default {
  props: ['config', 'forms'],
  computed: {
    selectedForm() {
      return this.forms.find(form => +form.id === +this.config.newsletter_form);
    }
  }
}
</script>

<style>

</style>
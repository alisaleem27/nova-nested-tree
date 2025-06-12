import FormField from './components/FormField.vue';
import DetailField from './components/DetailField.vue';

Nova.booting((app, store) => {
  app.component('form-nova-nested-tree-attach-many', FormField)
  app.component('detail-nova-nested-tree-attach-many', DetailField)
})

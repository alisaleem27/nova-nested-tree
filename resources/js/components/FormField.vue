<template>
  <default-field :field="field" :errors="errors" :show-help-text="false" class="categories-tree">
    <template #field>
      <div class="flex">
        <div :dir="field.rtl ? 'rtl' : 'ltr'" class="w-full nova-tree-attach-many">
          <treeselect v-model="selectedValues"
                      :id="field.name"
                      :multiple="field.multiple"
                      :options="field.options"
                      :flatten-search-results="true"
                      :flat="field.flatten"
                      :searchable="field.searchable"
                      :always-open="field.alwaysOpen"
                      :disabled="field.disabled"
                      :sort-value-by="field.sortValueBy"
                      :placeholder="field.placeholder"
                      :max-height="field.maxHeight"
                      :value-consists-of="field.valueConsistsOf"
                      :normalizer="normalizer"
                      :disableFuzzyMatching="true"
                      v-if="renderComponent"

          >
            <label slot="option-label" slot-scope="{ node, shouldShowCount, count, labelClassName, countClassName }"
                   class="option-label" :class="node.raw.boolean_is_active? labelClassName: labelClassName+' text-60'">
              {{ node.label }}
            </label>
          </treeselect>
        </div>
        <div class="w-3/5 pl-8 pt-2" v-if="field.displayPath && fullUrl">
          <ul class="category-list pl-6">
            <li class="py-1 text-80 text-sm capitalize" v-for="(url,index) in fullUrl" :key="index"><strong>{{ url[0] }}</strong> {{ url[1] }}</li>
          </ul>
        </div>
      </div>
    </template>
  </default-field>
</template>

<style type="text/css">
.categories-tree .py-6.px-8.w-1\/2 { width: 80%; }
.category-list { list-style: none;}

.vue-treeselect__multi-value-item {
  background-color: rgba(var(--colors-primary-500),0.2);
  color: rgba(var(--colors-primary-500));
}

.vue-treeselect__value-remove {
  color: rgba(var(--colors-primary-500));
}
.vue-treeselect__checkbox--checked {
  border-color: rgba(var(--colors-primary-500));
  background-color: rgba(var(--colors-primary-500));
}

</style>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'

import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  components: {Treeselect},
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data()
  {
    return {
      selectedValues: null,
      fullUrl:null,
      renderComponent: true,
    };
  },
  watch: {
    selectedValues: function(val,old) {
      //do something when the data changes.
      let diff = [],diff_reverse = [];
      if(old != null){
        diff = val.filter(x => old.indexOf(x) === -1);
        diff_reverse = old.filter(x => val.indexOf(x) === -1);
      }
      if (val && (diff.length > 0 || diff_reverse.length > 0)) {
        this.getCategoryFullPath(val);
      }
    }
  },
  methods: {
    forceRerender() {
      // Remove my-component from the DOM
      this.renderComponent = false;

      // If you like promises better you can
      // also use nextTick this way
      this.$nextTick().then(() => {
        // Add the component back in
        this.renderComponent = true;
      });
    },
    decodeCategoryName(options,self){
      return options.filter(function (val) {
        val['category_name'] = _.unescape(val['category_name']);
        if (val.children.length > 0){
          val['children'] = self.decodeCategoryName(val.children,self);
        }
        return val;
      });
    },
    getCategoryFullPath(){
      if (this.field.displayPath && this.selectedValues != null && this.selectedValues.length > 0)
      {
        Nova.request().get('/nova-vendor/nova-nested-tree-attach-many/get-category-full-path',{
          params:{
            categories: this.selectedValues,
          }
        })
            .then( ( data ) => {
              this.fullUrl = data.data;
            } );
      }
    },
    normalizer( node )
    {
      return {
        id: node[this.field.idKey],
        label: node[this.field.labelKey],
        isDisabled: node.hasOwnProperty(this.field.activeKey) && node[this.field.activeKey] === this.field.isActiveFalse,
        children: node.hasOwnProperty(this.field.childrenKey)
        && node[this.field.childrenKey].length > 0
            ? node[this.field.childrenKey]
            : false
      }
    },
    setInitialValue()
    {

      let baseUrl = '/nova-vendor/nova-nested-tree-attach-many/';

      if( this.resourceId )
      {
        const url = [
          baseUrl + this.resourceName,
          this.resourceId,
          'attached',
          this.field.attribute,
          this.field.idKey
        ];

        Nova.request().get( url.join('/') ).then( response => { 
          if(!this.field.multiple)
          {
            this.selectedValues = response.data || undefined;
          }
          else
          {
            this.selectedValues = response.data || [];
          }
          this.forceRerender();
          if(this.selectedValues.length > 0){
            this.getCategoryFullPath(this.selectedValues);
          }
        });
      }
      else
      {
        this.selectedValues = this.field.multiple?[]:undefined;
      }
    },
    fill( formData )
    {
      formData.append( this.field.attribute, JSON.stringify( this.selectedValues ) )
    },
  },
  created(){
    let self = this;
    this.field.options.filter(function (val,index) {
      val['category_name'] = _.unescape(val['category_name']);
      if (val.children.length > 0){
        val["children"] = self.decodeCategoryName(val.children,self);
      }
      return val;
    });
  }
}
</script>


# Nova Nested Tree Attach Many
Support Laravel nova 4.
for nova 3 , use <a href="https://github.com/phoenix-lib/nova-nested-tree-attach-many">this package</a>.
### Suppot:


Belongs To Many Field for simple manage Nested relation tree. Enables attaching relationships easily.

![nova-nested-tree-attach-many](https://user-images.githubusercontent.com/74270064/98738291-8a872780-23b8-11eb-8c76-a8605abe69f8.gif)

### RoadMap

- [x] Validation
- [x] Show selected categories on Detail
- [ ] Ability to pass your own tree
- [ ] Ability to `Delayed Loading` data when tree has many records ( example 10k+ ).

### Installation

```bash
composer require zakariatlilani/nova-nested-tree
```

### Usage

This field uses tree provided by kalnoy/nestedset package

This field uses riophae/vue-treeselect under the hood

```php
use zakariatlilani\NovaNestedTree\NestedTreeAttachManyField;
```
```php
public function fields(Request $request)
{
    return [
        NestedTreeAttachManyField::make('Offer Categories',"categories","App\Nova\Category"),
    ];
}
```

Your model should has NodeTrait form package kalnoy/nestedset see RoadMap

```php

class Category extends Model
{
    use NodeTrait;
}
```

### Options

Here are a few customization options

- `->searchable(bool $searchable)`
- `->withIdKey(string $idKey = 'id')` // - id column name in your nested model
- `->withLabelKey(string $labelKey = 'name')` // - label column name in your nested model
- `->withActiveKey(string $activeKey)` // - active_status column name in your nested model used for disable options
- `->withChildrenKey(string $childrenKey)` // - children key in your nested model
- `->withPlaceholder(string $placeholder)` // - placeholder in tree select
- `->withMaxHeight(int $maxHeight)`
- `->withSortValueBy(string $sortBy)` // - @see https://vue-treeselect.js.org/#flat-mode-and-sort-values
- `->withAlwaysOpen(bool $alwaysOpen)` // - by default select is open, but you can change it behavior
- `->withFlatten(bool $flatten)` // - by default flatten is enabled, but you can change it behavior
- `->useSingleSelect()` // - ability for select only one value
- `->useAsField()` // - ability to write result to field without relations

### Example of use with package whitecube/nova-flexible-content

```php
use Whitecube\NovaFlexibleContent\Flexible;
use zakariatlilani\NovaNestedTree\NestedTreeAttachManyField;

/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        // ...

        Flexible::make('Content')
            ->addLayout('Simple content section', 'tree_section', [
                NestedTreeAttachManyField::make('Tree',"tree","App\Nova\Category")
                    ->useAsField(),   
            ])
    ];
}    
```

### Authorization
This field also respects policies: ie Role / Permission
- RolePolicy: attachAnyPermission($user, $role)
- RolePolicy: attachPermission($user, $role, $permission)
- PermissionPolicy: viewAny($user)

### Validation
You can set min, max, size, required or custom rule objects

```php
->rules('min:5', 'max:10', 'size:10', 'required', new CustomRule)`
```

### Contributing
Feel free to suggest changes, ask for new features or fix bugs yourself.

Hope this package will be useful for you.

### credit
<a href="https://github.com/phoenix-lib/nova-nested-tree-attach-many">phoenix-lib</a>

---
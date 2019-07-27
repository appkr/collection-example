## Collection Example

- Illuminate Collection
- First Class Collection
- Composite Pattern

### 3 types of operations
```
+--------+    +--------+    +--------+    +--------+    +--------+
| Source | -> | filter | -> | sortBy | -> | map    | -> | all    |
+--------+    +--------+    +--------+    +--------+    +--------+
```
- source: scalar/associative array, Collection
- intermediate(returns a Collection): `map`, `filter`, `where`, `sortBy`...
- terminal(returns a non-Collection): `all`, `implode`, `reduce`, ...

### Mutable vs immutable
- mutable: `each`
- immutable: `filter`

### To start from scratch
```bash
mkdir collection-example
cd collection-example

git init
echo ".idea" >> .gitignore
echo "vendor" >> .gitignore

echo "{}" > composer.json
composer require "illuminate/support" "nesbot/carbon"
composer require "phpunit/phpunit" --dev

mkdir src tests

git add . && git commit -m 'Project created'
```

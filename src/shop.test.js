import test from 'node:test';
import assert from 'node:assert/strict';
import { addItem, validCart, total } from './shop.js';

test('sizes remain separate and repeated additions update totals', () => {
  let cart = addItem([], 'wave', 'S');
  cart = addItem(cart, 'wave', 'M');
  cart = addItem(cart, 'wave', 'S');
  assert.equal(cart.length, 2);
  assert.equal(cart[0].quantity, 2);
  assert.equal(total(cart), 3870);
});
test('invalid saved products, sizes and quantities are discarded', () => {
  assert.deepEqual(validCart([null, {id:'missing',size:'S',quantity:1},{id:'wave',size:'bad',quantity:1},{id:'wave',size:'S',quantity:-1},{id:'wave',size:'S',quantity:1}]), [{id:'wave',size:'S',quantity:1}]);
  assert.deepEqual(validCart({}), []);
  assert.deepEqual(addItem([], 'wave', 'bad'), []);
});
test('quantity is capped at 99', () => {
  assert.equal(addItem([{id:'wave',size:'S',quantity:99}], 'wave', 'S')[0].quantity, 99);
});

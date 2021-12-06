<template>
    <div class="container">

        <form>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="hmocode">HMO Code</label>
                        <input id="hmocode" v-model="hmo.code" :name="hmo.code" class="form-control" type="text">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="hmoname">HMO Name</label>
                        <input id="hmoname" v-model="hmo.name" :name="hmo.name" class="form-control" type="text">
                    </div>
                </div>
                <div class="col">

                </div>
            </div>


            <table class="table table-borderless">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Sub Total</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product, index) in products" :key="index">
                    <td>
                        <input v-model="product.item" :name="`products[${index}][item]`" class="form-control"
                               type="text">
                    </td>
                    <td>
                        <input v-model="product.price" :name="`products[${index}][price]`" class="form-control"
                               type="number">
                    </td>
                    <td>
                        <input v-model="product.qty" :name="`products[${index}][qty]`" class="form-control"
                               type="number">
                    </td>
                    <td>
                        <input v-model="product.subtotal" :name="`products[${index}][subtotal]`" class="form-control"
                               readonly type="number">
                    </td>
                    <td>
                        <button class="btn btn-secondary" type="button" @click="removeItem(index)">-</button>
                    </td>
                </tr>


                <tr>
                    <td>
                        <button class="btn btn-secondary" type="button" @click="addItem">+</button>
                    </td>
                    <td>
                    </td>
                    <td>
                        Total
                    </td>
                    <td>
                        <input v-model="total" :name="total" class="form-control" readonly type="number">
                    </td>
                    <td>
                    </td>
                </tr>

                </tbody>
            </table>


            <hr>

            <div v-show="products.length > 0" class="form-group">
                <button class="btn btn-primary" type="button" @click="submit">Submit</button>
            </div>
        </form>


    </div>

</template>

<script>
export default {
    name: "SubmitOrder",
    mounted() {
        console.log('Component mounted.')
    },
    data() {
        return {
            hmo: {
                code: 'hmo-0-1',
                name: 'HMO 01',
                date: '',
            },
            products: [
                {
                    item: "Batch 1",
                    price: 200,
                    qty: 4,
                    subtotal: 0
                },
                {
                    item: "Batch 2",
                    price: 300,
                    qty: 5,
                    subtotal: 0
                },
            ],
        };
    },
    watch: {
        products: {
            handler: function (newval, oldval) {
                this.products.forEach(p => {
                    p.subtotal = p.price * p.qty;
                });
            }, deep: true,
            immediate: true
        }
    },
    computed: {
        total: function () {
            return this.products.reduce(function (total, product) {
                return total + Number(product.subtotal);
            }, 0);
        },
        formData: function () {
            return {
                hmo: this.hmo,
                products: this.products,
                total: this.total,
            }
        },
    },
    methods: {
        addItem() {
            this.products.push({
                item: 0,
                price: 0,
                qty: 0,
                subtotal: 0
            })
        },
        removeItem(index) {
            this.products.splice(index, 1);
        },

        submit() {
            const data = this.formData;
            alert(JSON.stringify(data, null, 2))
        }
    }
}
</script>

<style>
.table th, .table td {
    padding: 0.25rem;
    vertical-align: top;
}
</style>

<template>
    <div class="modal" v-if="showModal">
        <div class="modal-content">
            <h2>Filtered and Sorted Reviews</h2>
            <table>
                <thead>
                <tr>
                    <th>Rating</th>
                    <th>Text</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="review in reviews" :key="review.id">
                    <td>{{ review.rating }}</td>
                    <td>{{ review.reviewFullText }}</td>
                    <td>{{ review.reviewCreatedOnDate }}</td>
                </tr>
                </tbody>
            </table>
            <button @click="closeModal">Close</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        reviews: {
            type: Array,
            required: true,
        },
        showModal: {
            type: Boolean,
            required: true,
        },
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
    },
};
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fff;
    width: 80%;
    padding: 20px;
}
</style>

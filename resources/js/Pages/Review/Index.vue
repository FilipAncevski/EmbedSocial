<template>
    <div class="w-4/5 mx-auto">
        <h1 class="text-3xl font-bold mb-4">Reviews</h1>

        <!-- Filter Form -->
        <div class="mb-4">
            <label for="prioritize_text" class="mr-2">Prioritize by Text:</label>
            <select v-model="form.prioritizeText" name="prioritize_text" id="prioritize_text" class="p-2 border border-gray-300 rounded">
                <option value="">-- Select --</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="sort_rating" class="mr-2">Sort by Rating:</label>
            <select v-model="form.sortRating" name="sort_rating" id="sort_rating" class="p-2 border border-gray-300 rounded">
                <option value="">-- Select --</option>
                <option value="highest">Highest First</option>
                <option value="lowest">Lowest First</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="sort_date" class="mr-2">Sort by Date:</label>
            <select v-model="form.sortDate" name="sort_date" id="sort_date" class="p-2 border border-gray-300 rounded">
                <option value="">-- Select --</option>
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="min_rating" class="mr-2">Minimum Rating:</label>
            <select v-model="form.minRating" name="min_rating" id="min_rating" class="p-2 border border-gray-300 rounded">
                <option value="">-- Select --</option>
                <option value="5">5</option>
                <option value="4">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
            </select>
        </div>

        <div>
            <button type="submit" @click="applyFilters">Apply Filters</button>
        </div>

        <ReviewTable :reviews="reviews" :showModal="showModal" @close="showModal=false" />
    </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import ReviewTable from '@/Components/ReviewTable.vue';

export default {
    components: {
        ReviewTable,
    },

    setup() {
        const form = ref({
            prioritizeText: '',
            sortRating: '',
            sortDate: '',
            minRating: '',
        });

        const showModal = ref(false);
        const reviews = ref([]);

        const applyFilters = () => {
            if (validateForm()) {
                axios
                    .get('/reviews/show', {
                        params: form.value,
                    })
                    .then((response) => {
                        const data = response.data;
                        if (Array.isArray(data.reviews)) {
                            reviews.value = data.reviews;
                            showModal.value = true;
                        } else {
                            console.error('Invalid reviews data:', data);
                        }
                    })
                    .catch((error) => {
                        console.error('Failed to fetch reviews:', error);
                    });
            }
        };

        const validateForm = () => {
            if (form.value.prioritizeText === '') {
                alert('Please select a value for Prioritize by Text');
                return false;
            }

            if (form.value.sortRating === '') {
                alert('Please select a value for Sort by Rating');
                return false;
            }

            if (form.value.sortDate === '') {
                alert('Please select a value for Sort by Date');
                return false;
            }

            if (form.value.minRating === '') {
                alert('Please select a value for Minimum Rating');
                return false;
            }

            return true;
        };

        return {
            form,
            showModal,
            reviews,
            applyFilters,
        };
    },
};
</script>

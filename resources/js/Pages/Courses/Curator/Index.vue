<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import CuratorLayout from '@/Layouts/CuratorLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TagsClickable from '@/Components/TagsClickable.vue';
import InputLabel from '@/Components/InputLabel.vue';

const page = usePage();
const courses = ref(page.props.courses);
const tags = ref(page.props.tags || []);

const sortOrder = ref('newest');
const form = ref({
  selectedTags: [],
});

const sortedCourses = computed(() => {
  if (sortOrder.value === 'newest') {
    return courses.value.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  } else if (sortOrder.value === 'oldest') {
    return courses.value.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  }
});

const filteredCourses = computed(() => {
  let courses = sortedCourses.value;

  if (form.value.selectedTags.length !== 0) {
    courses = courses.filter(course => {
      return form.value.selectedTags.every(tagId => course.tags.some(tag => tag.id === tagId));
    });
  } return courses;
});

const dropdownVisible = ref(false);

const toggleDropdown = () => {
  dropdownVisible.value = !dropdownVisible.value;
};

const closeDropdown = () => {
  dropdownVisible.value = false;
};


</script>

<template>
  <Head title="Kursai " />

  <CuratorLayout>
    <div class="text-center">
      <h1 class="text-emerald-700 font-semibold text-3xl mt-6">Mano kursai</h1>
      <p class=" text-emerald-800 mt-4 text-md"><span class="font-semibold">Čia matomi visi jūsų sukurti
          kursai.</span> Norėdami sukurti naują kursą, spauskite <span class="font-semibold uppercase">sukurti naują
          kursą</span>.</p>
    </div>

    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="pb-8">
          <PrimaryButton @click="router.get(route('courses.create'))" class="py-2 px-4 rounded-lg">
            Sukurti naują kursą
          </PrimaryButton>

          <div class="mt-5 p-1 border-l-4 border-emerald-700">
            <p class="text-left text-emerald-800 pl-1">Jūsų sukurtų kursų: {{ courses.length }}</p>
          </div>
        </div>

        <div class="mb-9 flex items-center justify-end space-x-6">
          <div class="relative inline-block text-left">
            <button type="button"
              class="rounded-md inline-flex justify-center items-center text-xs shadow-md px-2 py-1 bg-emerald-700 hover:bg-teal-800"
              id="filter-menu" aria-haspopup="true" aria-expanded="true" @click="toggleDropdown">
              Filtruoti pagal žymas
            </button>
            <div v-show="dropdownVisible"
              class="rounded-lg origin-top-left absolute left-0 mt-2 w-72 shadow-lg bg-white ring-1 ring-black ring-opacity-5">
              <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="filter-menu">
                <div class="flex justify-between items-center px-2 py-2">
                  <div>
                    <TagsClickable :tags="tags || []" v-model="form.selectedTags" class="inline justify-evenly" />
                  </div>
                  <button @click="closeDropdown" class="focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 hover:text-gray-800" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="flex items-center">
            <InputLabel for="sortOrder" class="text-sm block font-medium text-gray-700">Rikiuoti pagal:</InputLabel>
            <select v-model="sortOrder" id="sortOrder"
              class="text-xs text-black form-select block w-46 h-9 ml-2 rounded-md shadow-md">
              <option value="newest">Naujausius</option>
              <option value="oldest">Seniausius</option>
            </select>
          </div>
        </div>

        <div v-if="courses.length === 0" class="bg-emerald-500 shadow-lg rounded-lg p-6 text-center">
          <h3 class="text-2xl font-semibold text-white">Jūs dar neturite jokių savo sukurtų kursų!</h3>
          <p class="m-4 text-gray-100">Pradėkite nuo naujo kurso sukūrimo ir turinio pridėjimo.</p>
          <PrimaryButton @click="router.get(route('courses.create'))"
            class="py-2 px-4 rounded-lg bg-emerald-700 hover:bg-emerald-600">
            Sukurti naują kursą
          </PrimaryButton>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="course in filteredCourses" :key="course.id"
            class="bg-white overflow-hidden shadow-lg rounded-lg p-4">
            <Link :href="route('courses.show', course.id)"
              class="text-emerald-700 hover:text-emerald-900 hover:border-b hover:border-black font-semibold text-xl">
            {{ course.title }}
            </Link>
            <p class="text-black mt-2">{{ course.description.length > 100 ? course.description.substring(0, 100) + '...' :
              course.description }}</p>
            <div class="mt-4">
              <PrimaryButton @click="router.get(route('courses.show', course.id))">
                Peržiūrėti
              </PrimaryButton>
            </div>
          </div>

        </div>
      </div>
    </div>
  </CuratorLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import '@fortawesome/fontawesome-free/css/all.css';

const tasks = ref([]);
const selectedTask = ref(null);
const isEditModalOpen = ref(false);
const isAddTaskModalOpen = ref(false); 
const updatedTitle = ref(''); 
const updatedDescription = ref(''); 
const newTaskTitle = ref(''); 
const newTaskDescription = ref(''); 


const fetchTasks = async () => {
  const response = await fetch('/tasks');
  tasks.value = await response.json();
};


const deleteTask = (id) => {
  Inertia.delete(`/tasks/${id}`, {
    onSuccess: fetchTasks,
  });
};


const openEditModal = (task) => {
  selectedTask.value = task;
  updatedTitle.value = task.title;
  updatedDescription.value = task.description; 
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
  selectedTask.value = null;
};


const updateTask = () => {
  if (selectedTask.value) {
    Inertia.put(`/tasks/${selectedTask.value.id}`, {
      title: updatedTitle.value,
      description: updatedDescription.value,
    }, {
      onSuccess: () => {
        closeEditModal();
        fetchTasks();
      },
    });
  }
};


const openAddTaskModal = () => {
  isAddTaskModalOpen.value = true;
};


const closeAddTaskModal = () => {
  isAddTaskModalOpen.value = false;
  newTaskTitle.value = ''; 
  newTaskDescription.value = '';
};


const addTask = () => {
  Inertia.post('/tasks', {
    title: newTaskTitle.value,
    description: newTaskDescription.value,
  }, {
    onSuccess: () => {
      closeAddTaskModal();
      fetchTasks(); 
    },
  });
};

onMounted(fetchTasks);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 d-fle" style="float: inline-end;">
                        <div>
                            
                            <button @click="openAddTaskModal" class="btn-add">Add Task</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5" v-for="task in tasks" :key="task.id">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 d-fle">
                        <div>
                            <h2 class="title">Title</h2>
                            <div>{{ task.title }}</div>
                            <h2 class="title">Description</h2>
                            <div>{{ task.description }}</div>
                        </div>
                        <div>
                            <button @click="openEditModal(task)" class="btn-upd"><i class="fas fa-edit c-w"></i></button>
                            <button @click="deleteTask(task.id)" class="btn-rmv"><i class="fas fa-trash c-w"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isAddTaskModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold">Add New Task</h2>
                <input 
                  type="text" 
                  v-model="newTaskTitle" 
                  class="border p-2 w-full mt-2" 
                  placeholder="Enter task title" 
                />
                <textarea 
                  v-model="newTaskDescription" 
                  class="border p-2 w-full mt-2" 
                  placeholder="Enter task description" 
                ></textarea>
                <div class="flex justify-end mt-4">
                    <button @click="closeAddTaskModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button @click="addTask" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                </div>
            </div>
        </div>

        <div v-if="isEditModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold">Edit Task</h2>
                <input 
                  type="text" 
                  v-model="updatedTitle" 
                  class="border p-2 w-full mt-2" 
                  placeholder="Enter new title" 
                />
                <textarea 
                  v-model="updatedDescription" 
                  class="border p-2 w-full mt-2" 
                  placeholder="Enter new description" 
                ></textarea>
                <div class="flex justify-end mt-4">
                    <button @click="closeEditModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button @click="updateTask" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.d-fle {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.title {
    font-weight: bold;
    font-size: 21px;
    margin-bottom: 7px;
}

.btn-add {
    background-color: green;
    padding: 10px;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    border: none;
}

.btn-upd {
    background-color: blue;
    padding: 5px;
    border-radius: 100%;
    width: 32px;
    margin-right: 5px;
}

.btn-rmv {
    background-color: red;
    padding: 5px;
    border-radius: 100%;
    width: 32px;
    margin-right: 5px;
}

.c-w {
    color: white;
}
</style>

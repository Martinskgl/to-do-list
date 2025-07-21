<template>
    <div class="todo-container">
        <div class="card">
            <div class="card-header">
                <h3>Lista de Tarefas</h3>
            </div>
            <div class="card-body">
                <!-- Formulário para adicionar nova tarefa -->
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Digite uma nova tarefa..."
                        v-model="newTask"
                        @keyup.enter="addTask"
                    />
                    <button
                        class="btn btn-primary"
                        type="button"
                        @click="addTask"
                        :disabled="!newTask.trim()"
                    >
                        Adicionar
                    </button>
                </div>

                <!-- Lista de tarefas -->
                <div
                    v-if="tasks.length === 0"
                    class="text-muted text-center py-4"
                >
                    Nenhuma tarefa adicionada ainda.
                </div>

                <ul class="list-group" v-else>
                    <li
                        v-for="(task, index) in tasks"
                        :key="task.id"
                        class="list-group-item d-flex justify-content-between align-items-center"
                        :class="{
                            'text-decoration-line-through text-muted':
                                task.completed,
                        }"
                    >
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="task.completed"
                                :id="'task-' + task.id"
                            />
                            <label
                                class="form-check-label"
                                :for="'task-' + task.id"
                            >
                                {{ task.text }}
                            </label>
                        </div>
                        <button
                            class="btn btn-danger btn-sm"
                            @click="removeTask(index)"
                        >
                            Remover
                        </button>
                    </li>
                </ul>

                <!-- Estatísticas -->
                <div class="mt-3 text-muted small" v-if="tasks.length > 0">
                    Total: {{ tasks.length }} | Concluídas:
                    {{ completedTasks }} | Pendentes: {{ pendingTasks }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from "vue";

export default {
    name: "TodoList",
    setup() {
        const newTask = ref("");
        const tasks = ref([]);
        let nextId = 1;

        const addTask = () => {
            if (newTask.value.trim()) {
                tasks.value.push({
                    id: nextId++,
                    text: newTask.value.trim(),
                    completed: false,
                });
                newTask.value = "";
            }
        };

        const removeTask = (index) => {
            tasks.value.splice(index, 1);
        };

        const completedTasks = computed(() => {
            return tasks.value.filter((task) => task.completed).length;
        });

        const pendingTasks = computed(() => {
            return tasks.value.filter((task) => !task.completed).length;
        });

        return {
            newTask,
            tasks,
            addTask,
            removeTask,
            completedTasks,
            pendingTasks,
        };
    },
};
</script>

<style scoped>
.todo-container {
    max-width: 600px;
    margin: 0 auto;
}

.form-check-label {
    cursor: pointer;
}

.list-group-item {
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>

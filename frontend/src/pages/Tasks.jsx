import { useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";
import api from "../api/axios";
import { useAuth } from "../context/AuthContext";

function Tasks() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();
    const [tasks, setTasks] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");
    const [editingTaskId, setEditingTaskId] = useState(null);
    const [editTitle, setEditTitle] = useState("");
    const [editDescription, setEditDescription] = useState("");

    useEffect(() => {
        fetchTasks();
    }, []);

    async function fetchTasks() {
        try {
            const response = await api.get("/tasks");

            setTasks(response.data.data ?? response.data);
        } catch (error) {
            setError(
                error.response?.data?.message ||
                "Failed to load tasks"
            );
        } finally {
            setLoading(false);
        }
    }

    async function handleLogout() {
        await logout();
    }

    async function handleComplete(taskId) {
        try {
            const response = await api.post(
                `/tasks/${taskId}/complete`
            );

            const updatedTask =
                response.data.data ?? response.data;

            setTasks((currentTasks) =>
                currentTasks.map((task) =>
                    task.id === updatedTask.id
                        ? updatedTask
                        : task
                )
            );
        } catch (error) {
            setError(
                error.response?.data?.message ||
                "Failed to complete task"
            );
        }
    }

    function handleEdit(task) {
        setEditingTaskId(task.id);
        setEditTitle(task.title);
        setEditDescription(task.description || "");
    }

    async function handleDelete(taskId) {
        try {
            await api.delete(`/tasks/${taskId}`);

            setTasks((currentTasks) =>
                currentTasks.filter((task) => task.id !== taskId)
            );
        } catch (error) {
            setError(
                error.response?.data?.message ||
                "Failed to delete task"
            );
        }
    }

    async function handleUpdate(taskId) {
        try {
            const response = await api.put(`/tasks/${taskId}`, {
                title: editTitle,
                description: editDescription,
            });

            const updatedTask = response.data.data ?? response.data;

            setTasks((currentTasks) =>
                currentTasks.map((task) =>
                    task.id === updatedTask.id
                        ? updatedTask
                        : task
                )
            );

            setEditingTaskId(null);
            setEditTitle("");
            setEditDescription("");
        } catch (error) {
            setError(
                error.response?.data?.message ||
                "Failed to update task"
            );
        }
    }

    if (loading) {
        return <p>Loading tasks...</p>;
    }

    return (
        <div className="tasks-page">
            <h1>Tasks</h1>

            {user && (
                <p>
                    Welcome, {user.username}
                </p>
            )}

            <div className="tasks-actions">
                <button onClick={handleLogout}>
                    Logout
                </button>
                
                <button onClick={() => navigate("/tasks/create")}>
                    Create New Task
                </button>
            </div>

            {error && <p>{error}</p>}

            {tasks.length === 0 ? (
                <p>No tasks yet.</p>
            ) : (
                <div className="tasks-list">
                    {tasks.map((task) => (
                        <div
                            key={task.id}
                            className={`task-card ${
                                task.status === "completed" ? "completed" : ""
                            }`}
                        >
                                {editingTaskId === task.id ? (
                                <>
                                    <input
                                        type="text"
                                        value={editTitle}
                                        onChange={(event) => setEditTitle(event.target.value)}
                                        placeholder="Task title"
                                    />

                                    <textarea
                                        value={editDescription}
                                        onChange={(event) =>
                                            setEditDescription(event.target.value)
                                        }
                                        placeholder="Task description"
                                    />

                                    <button onClick={() => handleUpdate(task.id)}>
                                        Save
                                    </button>

                                    <button onClick={() => setEditingTaskId(null)}>
                                        Cancel
                                    </button>
                                </>
                            ) : (
                                <>
                                    <h3>{task.title}</h3>

                                    <p className="task-description">{task.description}</p>

                                    <p
                                        className={`task-status ${
                                            task.status === "completed"
                                                ? "status-completed"
                                                : "status-active"
                                        }`}
                                    >
                                        Status: {task.status}
                                    </p>

                                    <div className="task-buttons">

                                        {task.status === "active" && (
                                            <button onClick={() => handleComplete(task.id)}>
                                                Complete
                                            </button>
                                        )}

                                        <button onClick={() => handleEdit(task)}>
                                            Edit
                                        </button>

                                        <button onClick={() => handleDelete(task.id)}>
                                            Delete
                                        </button>

                                    </div>
                                </>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

export default Tasks;
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/axios";

function CreateTask() {
    const navigate = useNavigate();

    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");

    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    async function handleSubmit(event) {
        event.preventDefault();

        setError("");
        setLoading(true);

        try {
            await api.post("/tasks", {
                title,
                description,
            });

            navigate("/tasks");
        } catch (error) {
            const validationErrors = error.response?.data?.errors;

            if (validationErrors) {
                setError(
                    Object.values(validationErrors).flat().join(" ")
                );
            } else {
                setError(
                    "Could not create task. Please try again later."
                );
            }
        } finally {
                setLoading(false);
        }
    }

    return (
        <div>
            <h1>Create Task</h1>

            <form onSubmit={handleSubmit}>
                <div>
                    <label>Title</label>
                    <br />

                    <input
                        type="text"
                        value={title}
                        onChange={(event) =>
                            setTitle(event.target.value)
                        }
                        required
                        maxLength={255}
                    />
                </div>

                <br />

                <div>
                    <label>Description</label>
                    <br />

                    <textarea
                        value={description}
                        onChange={(event) =>
                            setDescription(event.target.value)
                        }
                        required
                        maxLength={2000}
                    />
                </div>

                <br />

                {error && <p>{error}</p>}
                <div className="form-actions">
                    <button
                        type="submit"
                        disabled={loading}
                    >
                        {loading ? "Creating..." : "Create task"}
                    </button>

                    <button
                        type="button"
                        onClick={() => navigate("/tasks")}
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    );
}

export default CreateTask;
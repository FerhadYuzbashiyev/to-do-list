import { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/axios";

function Register() {
    const navigate = useNavigate();

    const [username, setUsername] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    async function handleSubmit(event) {
        event.preventDefault();

        setError("");
        setLoading(true);

        try {
            const response = await api.post("/auth/register", {
                username,
                email,
                password,
            });

            navigate("/verify-otp", {
                state: {
                    userId: response.data.user_id,
                },
            });
        } catch (error) {
            const validationErrors = error.response?.data?.errors;

            if (validationErrors) {
                setError(
                    Object.values(validationErrors).flat().join(" ")
                );
            } else {
                setError(
                    "Could not create account. Please try again later."
                );
            }
        } finally {
            setLoading(false);
        }
    }

    return (
        <div>
            <h1>Create account</h1>

            <form onSubmit={handleSubmit}>
                <div>
                    <label>Username</label>

                    <input
                        type="text"
                        value={username}
                        onChange={(event) =>
                            setUsername(event.target.value)
                        }
                        required
                        maxLength={255}
                    />
                </div>

                <div>
                    <label>Email</label>

                    <input
                        type="email"
                        value={email}
                        onChange={(event) =>
                            setEmail(event.target.value)
                        }
                        required
                    />
                </div>

                <div>
                    <label>Password</label>

                    <input
                        type="password"
                        value={password}
                        onChange={(event) =>
                            setPassword(event.target.value)
                        }
                        required
                    />
                </div>

                {error && <p>{error}</p>}

                <button
                    type="submit"
                    disabled={loading}
                >
                    {loading ? "Creating..." : "Create account"}
                </button>

                <button
                    type="button"
                    onClick={() => navigate("/login")}
                >
                    Back to login
                </button>
            </form>
        </div>
    );
}

export default Register;
import { useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

function VerifyOtp() {
    const location = useLocation();
    const navigate = useNavigate();

    const { verifyOtp } = useAuth();

    const userId = location.state?.userId;

    const [code, setCode] = useState("");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    async function handleSubmit(event) {
        event.preventDefault();

        setError("");
        setLoading(true);

        try {
            await verifyOtp(userId, code);

            navigate("/tasks");

        } catch (error) {
            setError(
                error.response?.data?.message ||
                "Invalid or expired OTP"
            );
        } finally {
            setLoading(false);
        }
    }

    if (!userId) {
        return (
            <div>
                <h1>Verification error</h1>

                <p>
                    User ID is missing. Please login again.
                </p>

                <button onClick={() => navigate("/login")}>
                    Back to login
                </button>
            </div>
        );
    }

    return (
        <div>
            <h1>Verify OTP</h1>

            <p>
                We sent a verification code to your email.
            </p>

            <form onSubmit={handleSubmit}>
                <div>
                    <label>OTP code</label>

                    <input
                        type="text"
                        value={code}
                        onChange={(event) =>
                            setCode(event.target.value)
                        }
                        maxLength={4}
                        required
                    />
                </div>

                {error && <p>{error}</p>}

                <button
                    type="submit"
                    disabled={loading}
                >
                    {loading ? "Verifying..." : "Verify"}
                </button>
            </form>
        </div>
    );
}

export default VerifyOtp;
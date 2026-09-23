import { createContext, useContext, useEffect, useState } from "react";
import api from "../api/axios";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(
        localStorage.getItem("token")
    );
    const [loading, setLoading] = useState(true);

    const isAuthenticated = Boolean(token);

    useEffect(() => {
        if (!token) {
            setLoading(false);
            return;
        }

        fetchUser();
    }, [token]);

    async function fetchUser() {
        try {
            const response = await api.get("/auth/me");
            setUser(response.data.data ?? response.data);
        } catch (error) {
            localStorage.removeItem("token");
            setToken(null);
            setUser(null);
        } finally {
            setLoading(false);
        }
    }

    async function verifyOtp(userId, code) {
        const response = await api.post("/auth/verify-otp", {
            user_id: userId,
            code,
        });

        const newToken = response.data.token;

        localStorage.setItem("token", newToken);
        setToken(newToken);

        return response.data;
    }

    async function logout() {
        try {
            if (token) {
                await api.post("/auth/logout");
            }
        } finally {
            localStorage.removeItem("token");
            setToken(null);
            setUser(null);
        }
    }

    return (
        <AuthContext.Provider
            value={{
                user,
                token,
                isAuthenticated,
                loading,
                fetchUser,
                verifyOtp,
                logout,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    return useContext(AuthContext);
}
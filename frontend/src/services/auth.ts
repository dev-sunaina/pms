import api from './api';

export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'manager' | 'member';
  avatar?: string;
  created_at: string;
  updated_at: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export const authService = {
  async login(credentials: LoginCredentials): Promise<{ user: User; token: string }> {
    const response = await api.post('/login', credentials);
    return response.data;
  },

  async register(data: RegisterData): Promise<{ user: User; token: string }> {
    const response = await api.post('/register', data);
    return response.data;
  },

  async logout(): Promise<void> {
    await api.post('/logout');
    localStorage.removeItem('auth_token');
  },

  async getCurrentUser(): Promise<User> {
    const response = await api.get('/user');
    return response.data;
  },

  setToken(token: string): void {
    localStorage.setItem('auth_token', token);
  },

  getToken(): string | null {
    return localStorage.getItem('auth_token');
  },

  isAuthenticated(): boolean {
    return !!this.getToken();
  },
};
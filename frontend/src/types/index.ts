export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'manager' | 'member';
  avatar?: string;
  created_at: string;
  updated_at: string;
}

export interface Team {
  id: number;
  name: string;
  description?: string;
  owner_id: number;
  owner: User;
  users: User[];
  created_at: string;
  updated_at: string;
}

export interface Project {
  id: number;
  name: string;
  description?: string;
  client_name?: string;
  status: 'active' | 'completed' | 'on_hold';
  start_date?: string;
  end_date?: string;
  team_id: number;
  created_by: number;
  team: Team;
  createdBy: User;
  users: User[];
  tasks: Task[];
  created_at: string;
  updated_at: string;
}

export interface Task {
  id: number;
  title: string;
  description?: string;
  project_id: number;
  assigned_to?: number;
  created_by: number;
  status: 'todo' | 'in_progress' | 'review' | 'completed';
  priority: 'low' | 'medium' | 'high' | 'urgent';
  due_date?: string;
  estimated_hours?: number;
  actual_hours?: number;
  project: Project;
  assignedTo?: User;
  createdBy: User;
  timesheets: Timesheet[];
  created_at: string;
  updated_at: string;
}

export interface Timesheet {
  id: number;
  user_id: number;
  project_id: number;
  task_id?: number;
  description?: string;
  hours: number;
  date: string;
  billable: boolean;
  hourly_rate?: number;
  user: User;
  project: Project;
  task?: Task;
  created_at: string;
  updated_at: string;
}

export interface ChatMessage {
  id: number;
  user_id: number;
  team_id?: number;
  project_id?: number;
  message: string;
  type: 'text' | 'file' | 'image';
  user: User;
  team?: Team;
  project?: Project;
  created_at: string;
  updated_at: string;
}
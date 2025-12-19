import { useState } from 'react';
import { User } from './lib/mockData';
import Login from './components/Login';
import DashboardLayout from './components/DashboardLayout';
import AdminDashboard from './components/AdminDashboard';
import StudentDashboard from './components/StudentDashboard';
import TeacherDashboard from './components/TeacherDashboard';
import ProfilePage from './components/ProfilePage';
import LearningModule from './components/LearningModule';
import ForumModule from './components/ForumModule';
import QuizModule from './components/QuizModule';
import GameModule from './components/GameModule';
import { Toaster } from './components/ui/sonner';

export default function App() {
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [currentView, setCurrentView] = useState<string>('dashboard');

  const handleLogin = (user: User) => {
    setCurrentUser(user);
    setCurrentView('dashboard');
  };

  const handleLogout = () => {
    setCurrentUser(null);
    setCurrentView('dashboard');
  };

  const handleUpdateProfile = (updates: Partial<User>) => {
    if (currentUser) {
      setCurrentUser({ ...currentUser, ...updates });
    }
  };

  if (!currentUser) {
    return (
      <>
        <Login onLogin={handleLogin} />
        <Toaster />
      </>
    );
  }

  const renderContent = () => {
    switch (currentView) {
      case 'dashboard':
        if (currentUser.role === 'admin') {
          return <AdminDashboard />;
        } else if (currentUser.role === 'teacher') {
          return <TeacherDashboard />;
        } else {
          return <StudentDashboard user={currentUser} />;
        }
      
      case 'learning':
        return <LearningModule user={currentUser} />;
      
      case 'forum':
        return <ForumModule user={currentUser} />;
      
      case 'quizzes':
        return <QuizModule user={currentUser} />;
      
      case 'games':
        return <GameModule user={currentUser} />;
      
      case 'users':
        return <AdminDashboard />;
      
      case 'performance':
        return <TeacherDashboard />;
      
      case 'profile':
        return <ProfilePage user={currentUser} onUpdateProfile={handleUpdateProfile} />;
      
      default:
        return <StudentDashboard user={currentUser} />;
    }
  };

  return (
    <>
      <DashboardLayout
        user={currentUser}
        currentView={currentView}
        onViewChange={setCurrentView}
        onLogout={handleLogout}
      >
        {renderContent()}
      </DashboardLayout>
      <Toaster />
    </>
  );
}

import axios from "axios";
import { useState } from 'react';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from './ui/tabs';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';
import { User } from '../lib/mockData';
import logoImage from 'figma:asset/6d0bea1bca23c32f82a41ff83f01eb343f62b0e9.png';
import { toast } from 'sonner@2.0.3';

interface LoginProps {
  onLogin: (user: User) => void;
}

export default function Login({ onLogin }: LoginProps) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  
  // Sign up states
  const [signupName, setSignupName] = useState('');
  const [signupEmail, setSignupEmail] = useState('');
  const [signupPassword, setSignupPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [signupRole, setSignupRole] = useState<'student' | 'teacher'>('student');

  const handleLogin = async (e: React.FormEvent) => {
  e.preventDefault();

  try {
    const response = await axios.post("http://localhost:5000/login", {
      email,
      password,
    });

    toast.success("Login successful!");
    onLogin(response.data.user);
  } catch (error: any) {
    console.error(error);
    toast.error(error.response?.data?.error || "Invalid email or password.");
  }
};

const handleSignup = async (e: React.FormEvent) => {
  e.preventDefault();

  if (signupPassword !== confirmPassword) {
    toast.error("Passwords do not match!");
    return;
  }

  try {
    const response = await axios.post("http://localhost:5000/users", {
      name: signupName,
      email: signupEmail,
      password: signupPassword,
      role: signupRole,
    });

    toast.success("Account created successfully!");
    // Optionally auto-login
    onLogin({
      id: response.data.userId,
      name: signupName,
      email: signupEmail,
      role: signupRole,
    });
  } catch (error: any) {
    console.error(error);
    toast.error(error.response?.data?.error || "Sign up failed! Please check your details.");
  }
};



  const quickLogin = (role: 'admin' | 'teacher' | 'student') => {
    let user: User;
    
    if (role === 'admin') {
      user = {
        id: '1',
        email: 'admin@normninja.com',
        name: 'Admin User',
        role: 'admin',
        dateJoined: '2024-01-01',
        bio: 'System Administrator'
      };
    } else if (role === 'teacher') {
      user = {
        id: '2',
        email: 'teacher@normninja.com',
        name: 'Dr. Sarah Johnson',
        role: 'teacher',
        dateJoined: '2024-01-15',
        bio: 'Database Systems Professor'
      };
    } else {
      user = {
        id: '3',
        email: 'student1@normninja.com',
        name: 'John Smith',
        role: 'student',
        dateJoined: '2024-02-01',
        bio: 'Computer Science major'
      };
    }
    
    onLogin(user);
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
      <Card className="w-full max-w-md">
        <CardHeader className="text-center">
          <div className="flex justify-center mb-4">
            <img src={logoImage} alt="NormNinja Logo" className="h-16 w-auto" />
          </div>
          <CardTitle>NormNinja</CardTitle>
          <CardDescription>Normalize Your Learning, Elevate Your Skills</CardDescription>
        </CardHeader>
        <CardContent>
          <Tabs defaultValue="login" className="w-full">
            <TabsList className="grid w-full grid-cols-2">
              <TabsTrigger value="login">Login</TabsTrigger>
              <TabsTrigger value="signup">Sign Up</TabsTrigger>
            </TabsList>
            
            <TabsContent value="login" className="space-y-4">
              <form onSubmit={handleLogin} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    placeholder="Enter your email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="password">Password</Label>
                  <Input
                    id="password"
                    type="password"
                    placeholder="Enter your password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                  />
                </div>
                <Button type="submit" className="w-full">
                  Sign In
                </Button>
              </form>

              <div className="mt-6">
                <div className="relative">
                  <div className="absolute inset-0 flex items-center">
                    <div className="w-full border-t" />
                  </div>
                  <div className="relative flex justify-center">
                    <span className="bg-white px-2 text-sm text-gray-500">Quick Login (Demo)</span>
                  </div>
                </div>
                <div className="mt-4 grid grid-cols-3 gap-2">
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => quickLogin('admin')}
                    className="w-full"
                  >
                    Admin
                  </Button>
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => quickLogin('teacher')}
                    className="w-full"
                  >
                    Teacher
                  </Button>
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => quickLogin('student')}
                    className="w-full"
                  >
                    Student
                  </Button>
                </div>
              </div>
            </TabsContent>
            
            <TabsContent value="signup" className="space-y-4">
              <form onSubmit={handleSignup} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="signup-name">Full Name</Label>
                  <Input
                    id="signup-name"
                    type="text"
                    placeholder="Enter your full name"
                    value={signupName}
                    onChange={(e) => setSignupName(e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="signup-email">Email</Label>
                  <Input
                    id="signup-email"
                    type="email"
                    placeholder="Enter your email"
                    value={signupEmail}
                    onChange={(e) => setSignupEmail(e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="signup-role">Role</Label>
                  <Select value={signupRole} onValueChange={(value: 'student' | 'teacher') => setSignupRole(value)}>
                    <SelectTrigger id="signup-role">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="student">Student</SelectItem>
                      <SelectItem value="teacher">Teacher</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="signup-password">Password</Label>
                  <Input
                    id="signup-password"
                    type="password"
                    placeholder="Create a password"
                    value={signupPassword}
                    onChange={(e) => setSignupPassword(e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="confirm-password">Confirm Password</Label>
                  <Input
                    id="confirm-password"
                    type="password"
                    placeholder="Confirm your password"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    required
                  />
                </div>
                <Button type="submit" className="w-full">
                  Create Account
                </Button>
              </form>
            </TabsContent>
          </Tabs>
        </CardContent>
      </Card>
    </div>
  );
}

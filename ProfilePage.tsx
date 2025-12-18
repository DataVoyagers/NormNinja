import { useState } from 'react';
import { User } from '../lib/mockData';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Textarea } from './ui/textarea';
import { Avatar, AvatarFallback } from './ui/avatar';
import { Badge } from './ui/badge';
import { Separator } from './ui/separator';
import { UserCircle, Mail, Calendar, Edit2, Save, X } from 'lucide-react';
import { toast } from 'sonner@2.0.3';

interface ProfilePageProps {
  user: User;
  onUpdateProfile: (updates: Partial<User>) => void;
}

export default function ProfilePage({ user, onUpdateProfile }: ProfilePageProps) {
  const [isEditing, setIsEditing] = useState(false);
  const [formData, setFormData] = useState({
    name: user.name,
    email: user.email,
    bio: user.bio || ''
  });

  const handleSave = () => {
    onUpdateProfile(formData);
    setIsEditing(false);
    toast.success('Profile updated successfully');
  };

  const handleCancel = () => {
    setFormData({
      name: user.name,
      email: user.email,
      bio: user.bio || ''
    });
    setIsEditing(false);
  };

  const getInitials = (name: string) => {
    return name
      .split(' ')
      .map((n) => n[0])
      .join('')
      .toUpperCase();
  };

  const getRoleBadgeVariant = (role: string): "default" | "secondary" | "destructive" => {
    switch (role) {
      case 'admin':
        return 'destructive';
      case 'teacher':
        return 'default';
      default:
        return 'secondary';
    }
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>My Profile</CardTitle>
              <CardDescription>View and manage your account information</CardDescription>
            </div>
            {!isEditing ? (
              <Button onClick={() => setIsEditing(true)} variant="outline">
                <Edit2 className="h-4 w-4 mr-2" />
                Edit Profile
              </Button>
            ) : (
              <div className="flex gap-2">
                <Button onClick={handleCancel} variant="outline" size="sm">
                  <X className="h-4 w-4 mr-2" />
                  Cancel
                </Button>
                <Button onClick={handleSave} size="sm">
                  <Save className="h-4 w-4 mr-2" />
                  Save Changes
                </Button>
              </div>
            )}
          </div>
        </CardHeader>
        <CardContent className="space-y-6">
          {/* Profile Header */}
          <div className="flex items-start gap-6">
            <Avatar className="h-24 w-24">
              <AvatarFallback className="bg-indigo-600 text-white text-2xl">
                {getInitials(user.name)}
              </AvatarFallback>
            </Avatar>
            <div className="flex-1">
              {!isEditing ? (
                <>
                  <h3 className="text-xl">{user.name}</h3>
                  <p className="text-sm text-gray-600 mt-1">{user.email}</p>
                  <div className="flex items-center gap-2 mt-3">
                    <Badge variant={getRoleBadgeVariant(user.role)} className="capitalize">
                      {user.role}
                    </Badge>
                    <span className="text-sm text-gray-500">•</span>
                    <span className="text-sm text-gray-500">
                      Joined {new Date(user.dateJoined).toLocaleDateString('en-US', { 
                        month: 'long', 
                        year: 'numeric' 
                      })}
                    </span>
                  </div>
                </>
              ) : (
                <div className="space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor="name">Full Name</Label>
                    <Input
                      id="name"
                      value={formData.name}
                      onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="email">Email</Label>
                    <Input
                      id="email"
                      type="email"
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    />
                  </div>
                </div>
              )}
            </div>
          </div>

          <Separator />

          {/* Profile Details */}
          <div className="space-y-4">
            <h4>Account Information</h4>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="flex items-start gap-3">
                <div className="bg-gray-100 p-2 rounded">
                  <UserCircle className="h-5 w-5 text-gray-600" />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Role</p>
                  <p className="capitalize">{user.role}</p>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <div className="bg-gray-100 p-2 rounded">
                  <Calendar className="h-5 w-5 text-gray-600" />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Member Since</p>
                  <p>{new Date(user.dateJoined).toLocaleDateString('en-US', { 
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                  })}</p>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <div className="bg-gray-100 p-2 rounded">
                  <Mail className="h-5 w-5 text-gray-600" />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Email Address</p>
                  <p className="break-all">{user.email}</p>
                </div>
              </div>
            </div>
          </div>

          <Separator />

          {/* Bio Section */}
          <div className="space-y-3">
            <Label htmlFor="bio">Bio</Label>
            {!isEditing ? (
              <p className="text-gray-700">
                {user.bio || 'No bio added yet. Click "Edit Profile" to add one.'}
              </p>
            ) : (
              <Textarea
                id="bio"
                value={formData.bio}
                onChange={(e) => setFormData({ ...formData, bio: e.target.value })}
                placeholder="Tell us about yourself..."
                rows={4}
              />
            )}
          </div>
        </CardContent>
      </Card>

      {/* Additional Info for Students */}
      {user.role === 'student' && (
        <Card>
          <CardHeader>
            <CardTitle>Learning Stats</CardTitle>
            <CardDescription>Your learning journey at a glance</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="text-center p-4 bg-blue-50 rounded-lg">
                <p className="text-2xl text-blue-600">0</p>
                <p className="text-sm text-gray-600 mt-1">Certificates Earned</p>
              </div>
              <div className="text-center p-4 bg-green-50 rounded-lg">
                <p className="text-2xl text-green-600">0</p>
                <p className="text-sm text-gray-600 mt-1">Badges Collected</p>
              </div>
              <div className="text-center p-4 bg-purple-50 rounded-lg">
                <p className="text-2xl text-purple-600">0</p>
                <p className="text-sm text-gray-600 mt-1">Study Streak Days</p>
              </div>
            </div>
          </CardContent>
        </Card>
      )}

      {/* Additional Info for Teachers */}
      {user.role === 'teacher' && (
        <Card>
          <CardHeader>
            <CardTitle>Teaching Stats</CardTitle>
            <CardDescription>Your teaching impact</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="text-center p-4 bg-indigo-50 rounded-lg">
                <p className="text-2xl text-indigo-600">3</p>
                <p className="text-sm text-gray-600 mt-1">Students Enrolled</p>
              </div>
              <div className="text-center p-4 bg-orange-50 rounded-lg">
                <p className="text-2xl text-orange-600">5</p>
                <p className="text-sm text-gray-600 mt-1">Materials Created</p>
              </div>
              <div className="text-center p-4 bg-teal-50 rounded-lg">
                <p className="text-2xl text-teal-600">3</p>
                <p className="text-sm text-gray-600 mt-1">Quizzes Published</p>
              </div>
            </div>
          </CardContent>
        </Card>
      )}
    </div>
  );
}

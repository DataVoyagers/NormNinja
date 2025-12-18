import { useState } from 'react';
import { User, mockChapters, mockMaterials, Material } from '../lib/mockData';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Textarea } from './ui/textarea';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from './ui/dialog';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from './ui/select';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from './ui/accordion';
import { Badge } from './ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from './ui/tabs';
import { 
  BookOpen, 
  FileText, 
  Video, 
  File, 
  Presentation,
  Plus,
  Edit,
  Trash2,
  Search,
  Upload
} from 'lucide-react';
import { toast } from 'sonner@2.0.3';

interface LearningModuleProps {
  user: User;
}

export default function LearningModule({ user }: LearningModuleProps) {
  const [materials, setMaterials] = useState<Material[]>(mockMaterials);
  const [searchQuery, setSearchQuery] = useState('');
  const [isUploadDialogOpen, setIsUploadDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [selectedMaterial, setSelectedMaterial] = useState<Material | null>(null);
  const [formData, setFormData] = useState({
    title: '',
    type: 'article' as 'pdf' | 'video' | 'article' | 'slides',
    chapterId: 'ch1',
    content: ''
  });

  const isTeacher = user.role === 'teacher' || user.role === 'admin';

  const handleUploadMaterial = () => {
    const newMaterial: Material = {
      id: `m${materials.length + 1}`,
      ...formData,
      uploadedBy: user.id,
      uploadedAt: new Date().toISOString().split('T')[0]
    };
    setMaterials([...materials, newMaterial]);
    setIsUploadDialogOpen(false);
    setFormData({ title: '', type: 'article', chapterId: 'ch1', content: '' });
    toast.success('Material uploaded successfully');
  };

  const handleUpdateMaterial = () => {
    if (!selectedMaterial) return;
    
    setMaterials(materials.map(m =>
      m.id === selectedMaterial.id
        ? { ...m, ...formData }
        : m
    ));
    setIsEditDialogOpen(false);
    setSelectedMaterial(null);
    setFormData({ title: '', type: 'article', chapterId: 'ch1', content: '' });
    toast.success('Material updated successfully');
  };

  const handleDeleteMaterial = (materialId: string) => {
    setMaterials(materials.filter(m => m.id !== materialId));
    toast.success('Material deleted successfully');
  };

  const openEditDialog = (material: Material) => {
    setSelectedMaterial(material);
    setFormData({
      title: material.title,
      type: material.type,
      chapterId: material.chapterId,
      content: material.content
    });
    setIsEditDialogOpen(true);
  };

  const getMaterialIcon = (type: string) => {
    switch (type) {
      case 'pdf':
        return <File className="h-4 w-4" />;
      case 'video':
        return <Video className="h-4 w-4" />;
      case 'article':
        return <FileText className="h-4 w-4" />;
      case 'slides':
        return <Presentation className="h-4 w-4" />;
      default:
        return <FileText className="h-4 w-4" />;
    }
  };

  const getMaterialBadgeVariant = (type: string): "default" | "secondary" | "destructive" | "outline" => {
    switch (type) {
      case 'pdf':
        return 'destructive';
      case 'video':
        return 'default';
      case 'article':
        return 'secondary';
      default:
        return 'outline';
    }
  };

  const filteredMaterials = materials.filter(m =>
    m.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
    mockChapters.find(ch => ch.id === m.chapterId)?.title.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-6">
      {/* Header */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Learning Materials</CardTitle>
              <CardDescription>
                {isTeacher 
                  ? 'Upload and manage course materials for students'
                  : 'Access and study course materials organized by chapter'
                }
              </CardDescription>
            </div>
            {isTeacher && (
              <Button onClick={() => setIsUploadDialogOpen(true)}>
                <Upload className="h-4 w-4 mr-2" />
                Upload Material
              </Button>
            )}
          </div>
        </CardHeader>
        <CardContent>
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <Input
              placeholder="Search materials..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="pl-10"
            />
          </div>
        </CardContent>
      </Card>

      {/* Materials by Chapter */}
      <Accordion type="single" collapsible className="space-y-4">
        {mockChapters.map((chapter) => {
          const chapterMaterials = filteredMaterials.filter(m => m.chapterId === chapter.id);
          
          if (chapterMaterials.length === 0 && searchQuery) return null;
          
          return (
            <AccordionItem key={chapter.id} value={chapter.id} className="border rounded-lg px-6 bg-white">
              <AccordionTrigger className="hover:no-underline">
                <div className="flex items-center gap-4 text-left">
                  <div className="bg-indigo-100 p-3 rounded-lg">
                    <BookOpen className="h-5 w-5 text-indigo-600" />
                  </div>
                  <div className="flex-1">
                    <h4>{chapter.title}</h4>
                    <p className="text-sm text-gray-600 mt-1">{chapter.description}</p>
                  </div>
                  <Badge variant="outline">
                    {chapterMaterials.length} material{chapterMaterials.length !== 1 ? 's' : ''}
                  </Badge>
                </div>
              </AccordionTrigger>
              <AccordionContent className="pt-4">
                <div className="space-y-3">
                  {chapterMaterials.length > 0 ? (
                    chapterMaterials.map((material) => (
                      <div
                        key={material.id}
                        className="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                      >
                        <div className="p-2 bg-white rounded border">
                          {getMaterialIcon(material.type)}
                        </div>
                        <div className="flex-1">
                          <p className="text-sm">{material.title}</p>
                          <div className="flex items-center gap-2 mt-1">
                            <Badge variant={getMaterialBadgeVariant(material.type)} className="text-xs">
                              {material.type}
                            </Badge>
                            <span className="text-xs text-gray-500">
                              Uploaded {material.uploadedAt}
                            </span>
                          </div>
                        </div>
                        <div className="flex gap-2">
                          <Button variant="outline" size="sm">View</Button>
                          {isTeacher && (
                            <>
                              <Button
                                variant="ghost"
                                size="sm"
                                onClick={() => openEditDialog(material)}
                              >
                                <Edit className="h-4 w-4" />
                              </Button>
                              <Button
                                variant="ghost"
                                size="sm"
                                onClick={() => handleDeleteMaterial(material.id)}
                              >
                                <Trash2 className="h-4 w-4" />
                              </Button>
                            </>
                          )}
                        </div>
                      </div>
                    ))
                  ) : (
                    <p className="text-center text-gray-500 py-4">
                      No materials available for this chapter yet.
                    </p>
                  )}
                </div>
              </AccordionContent>
            </AccordionItem>
          );
        })}
      </Accordion>

      {/* Upload Material Dialog */}
      <Dialog open={isUploadDialogOpen} onOpenChange={setIsUploadDialogOpen}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Upload Learning Material</DialogTitle>
            <DialogDescription>
              Add new learning material for students
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="upload-title">Title</Label>
              <Input
                id="upload-title"
                value={formData.title}
                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                placeholder="Material title"
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="upload-chapter">Chapter</Label>
              <Select
                value={formData.chapterId}
                onValueChange={(value) => setFormData({ ...formData, chapterId: value })}
              >
                <SelectTrigger id="upload-chapter">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  {mockChapters.map(ch => (
                    <SelectItem key={ch.id} value={ch.id}>{ch.title}</SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="upload-type">Type</Label>
              <Select
                value={formData.type}
                onValueChange={(value: 'pdf' | 'video' | 'article' | 'slides') =>
                  setFormData({ ...formData, type: value })
                }
              >
                <SelectTrigger id="upload-type">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="article">Article</SelectItem>
                  <SelectItem value="pdf">PDF Document</SelectItem>
                  <SelectItem value="video">Video</SelectItem>
                  <SelectItem value="slides">Presentation Slides</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="upload-content">Content/URL</Label>
              <Textarea
                id="upload-content"
                value={formData.content}
                onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                placeholder="Content or URL to the material"
                rows={3}
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsUploadDialogOpen(false)}>
              Cancel
            </Button>
            <Button onClick={handleUploadMaterial}>Upload Material</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Edit Material Dialog */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Edit Learning Material</DialogTitle>
            <DialogDescription>
              Update the material information
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="edit-title">Title</Label>
              <Input
                id="edit-title"
                value={formData.title}
                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="edit-chapter">Chapter</Label>
              <Select
                value={formData.chapterId}
                onValueChange={(value) => setFormData({ ...formData, chapterId: value })}
              >
                <SelectTrigger id="edit-chapter">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  {mockChapters.map(ch => (
                    <SelectItem key={ch.id} value={ch.id}>{ch.title}</SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="edit-type">Type</Label>
              <Select
                value={formData.type}
                onValueChange={(value: 'pdf' | 'video' | 'article' | 'slides') =>
                  setFormData({ ...formData, type: value })
                }
              >
                <SelectTrigger id="edit-type">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="article">Article</SelectItem>
                  <SelectItem value="pdf">PDF Document</SelectItem>
                  <SelectItem value="video">Video</SelectItem>
                  <SelectItem value="slides">Presentation Slides</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="edit-content">Content/URL</Label>
              <Textarea
                id="edit-content"
                value={formData.content}
                onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                rows={3}
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsEditDialogOpen(false)}>
              Cancel
            </Button>
            <Button onClick={handleUpdateMaterial}>Update Material</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
}

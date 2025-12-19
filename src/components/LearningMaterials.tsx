import { useState } from 'react';
import { User } from '../../App';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../ui/card';
import { Input } from '../ui/input';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../ui/tabs';
import { mockChapters, mockMaterials, Material } from '../../lib/mockData';
import { 
  Search, 
  FileText, 
  Video, 
  BookOpen, 
  Presentation,
  Download,
  Eye,
  Clock
} from 'lucide-react';

interface LearningMaterialsProps {
  user: User;
}

export function LearningMaterials({ user }: LearningMaterialsProps) {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedChapter, setSelectedChapter] = useState<string | 'all'>('all');

  const filteredMaterials = mockMaterials.filter(material => {
    const matchesSearch = material.title.toLowerCase().includes(searchQuery.toLowerCase());
    const topic = mockChapters
      .flatMap(ch => ch.topics)
      .find(t => t.id === material.topicId);
    const matchesChapter = selectedChapter === 'all' || topic?.chapterId === selectedChapter;
    return matchesSearch && matchesChapter;
  });

  const getMaterialIcon = (type: Material['type']) => {
    switch (type) {
      case 'pdf':
        return <FileText className="w-5 h-5 text-red-600" />;
      case 'video':
        return <Video className="w-5 h-5 text-blue-600" />;
      case 'article':
        return <BookOpen className="w-5 h-5 text-green-600" />;
      case 'slides':
        return <Presentation className="w-5 h-5 text-orange-600" />;
    }
  };

  const getMaterialBadge = (type: Material['type']) => {
    const colors = {
      pdf: 'bg-red-100 text-red-700',
      video: 'bg-blue-100 text-blue-700',
      article: 'bg-green-100 text-green-700',
      slides: 'bg-orange-100 text-orange-700',
    };
    return colors[type];
  };

  const getTopicName = (topicId: string) => {
    const topic = mockChapters
      .flatMap(ch => ch.topics)
      .find(t => t.id === topicId);
    return topic?.title || 'Unknown Topic';
  };

  const getChapterName = (topicId: string) => {
    const topic = mockChapters
      .flatMap(ch => ch.topics)
      .find(t => t.id === topicId);
    const chapter = mockChapters.find(ch => ch.id === topic?.chapterId);
    return chapter?.title || 'Unknown Chapter';
  };

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-gray-900 mb-2">Learning Materials</h1>
        <p className="text-gray-600">Access course materials and resources</p>
      </div>

      {/* Search and Filters */}
      <Card>
        <CardContent className="pt-6">
          <div className="space-y-4">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                placeholder="Search materials by title..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-9"
              />
            </div>
            
            <Tabs value={selectedChapter} onValueChange={setSelectedChapter}>
              <TabsList className="w-full justify-start overflow-x-auto flex-wrap h-auto">
                <TabsTrigger value="all">All Chapters</TabsTrigger>
                {mockChapters.map((chapter) => (
                  <TabsTrigger key={chapter.id} value={chapter.id}>
                    Chapter {chapter.order}
                  </TabsTrigger>
                ))}
              </TabsList>
            </Tabs>
          </div>
        </CardContent>
      </Card>

      {/* Materials List */}
      <div className="space-y-4">
        {filteredMaterials.length === 0 ? (
          <Card>
            <CardContent className="py-12 text-center">
              <BookOpen className="w-12 h-12 text-gray-400 mx-auto mb-4" />
              <p className="text-gray-600">No materials found matching your search.</p>
            </CardContent>
          </Card>
        ) : (
          filteredMaterials.map((material) => (
            <Card key={material.id} className="hover:shadow-md transition-shadow">
              <CardContent className="p-6">
                <div className="flex items-start gap-4">
                  <div className="p-3 bg-gray-50 rounded-lg">
                    {getMaterialIcon(material.type)}
                  </div>
                  
                  <div className="flex-1 min-w-0">
                    <div className="flex items-start justify-between gap-4 mb-2">
                      <div className="flex-1">
                        <h3 className="text-gray-900 mb-1">{material.title}</h3>
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                          <span>{getTopicName(material.topicId)}</span>
                          <span>•</span>
                          <span>{getChapterName(material.topicId)}</span>
                        </div>
                      </div>
                      <Badge className={getMaterialBadge(material.type)}>
                        {material.type.toUpperCase()}
                      </Badge>
                    </div>
                    
                    <p className="text-sm text-gray-600 mb-4 line-clamp-2">
                      {material.content}
                    </p>
                    
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-4 text-xs text-gray-500">
                        <span className="flex items-center gap-1">
                          <Clock className="w-3 h-3" />
                          {material.uploadedAt.toLocaleDateString()}
                        </span>
                        <span>By {material.uploadedBy}</span>
                      </div>
                      
                      <div className="flex gap-2">
                        <Button size="sm" variant="outline">
                          <Eye className="w-4 h-4 mr-2" />
                          View
                        </Button>
                        <Button size="sm" variant="outline">
                          <Download className="w-4 h-4 mr-2" />
                          Download
                        </Button>
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))
        )}
      </div>

      {/* Materials by Chapter Overview */}
      {selectedChapter === 'all' && searchQuery === '' && (
        <Card>
          <CardHeader>
            <CardTitle>Materials by Chapter</CardTitle>
            <CardDescription>Overview of available materials in each chapter</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-3">
              {mockChapters.map((chapter) => {
                const chapterMaterials = mockMaterials.filter(m => {
                  const topic = chapter.topics.find(t => t.id === m.topicId);
                  return topic !== undefined;
                });
                
                return (
                  <div
                    key={chapter.id}
                    className="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer"
                    onClick={() => setSelectedChapter(chapter.id)}
                  >
                    <div>
                      <div className="text-sm text-gray-900">{chapter.title}</div>
                      <div className="text-xs text-gray-500">{chapter.description}</div>
                    </div>
                    <Badge variant="secondary">{chapterMaterials.length} materials</Badge>
                  </div>
                );
              })}
            </div>
          </CardContent>
        </Card>
      )}
    </div>
  );
}

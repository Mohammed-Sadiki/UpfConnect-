<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Liste de tous les groupes avec filtres
     */
    public function index(Request $request)
    {
        $query = Group::with(['creator', 'members'])
            ->withCount(['members as approved_members_count' => function ($q) {
                $q->where('status', 'approved');
            }]);

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Filtre par visibilité (par défaut public)
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        } else {
            $query->public();
        }

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $groups = $query->latest()->paginate(12);

        // Groupes de l'utilisateur connecté
        $myGroups = auth()->user()->approvedGroups()->with('creator')->get();

        // Catégories pour le filtre
        $categories = [
            'academic' => 'Académique',
            'club' => 'Club & Association',
            'project' => 'Projet',
            'career' => 'Carrière',
            'other' => 'Autre'
        ];

        return view('groups.index', compact('groups', 'myGroups', 'categories'));
    }

    /**
     * Afficher un groupe spécifique avec ses posts
     */
    public function show(Group $group)
    {
        $user = auth()->user();
        $isMember = $group->isMember($user);
        $isAdmin = $group->isAdmin($user);
        $isModerator = $group->isModerator($user);
        $hasPendingRequest = $group->hasPendingRequest($user);

        // Posts du groupe
        $posts = $group->posts()
            ->with(['user', 'comments.user', 'likedByUsers'])
            ->latest()
            ->paginate(10);

        // Membres approuvés (limité pour sidebar)
        $members = $group->members()
            ->wherePivot('status', 'approved')
            ->take(10)
            ->get();

        // Nombre total de membres
        $totalMembers = $group->memberships()->approved()->count();

        // Événements à venir liés au groupe (si applicable)
        $upcomingEvents = [];

        return view('groups.show', compact(
            'group',
            'posts',
            'members',
            'totalMembers',
            'isMember',
            'isAdmin',
            'isModerator',
            'hasPendingRequest',
            'upcomingEvents'
        ));
    }

    /**
     * Formulaire de création de groupe
     */
    public function create()
    {
        $categories = [
            'academic' => 'Académique',
            'club' => 'Club & Association',
            'project' => 'Projet',
            'career' => 'Carrière',
            'other' => 'Autre'
        ];

        return view('groups.create', compact('categories'));
    }

    /**
     * Stocker un nouveau groupe
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:groups',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:academic,club,project,career,other',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:5120', // 5MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = ImageUploadService::upload(
                    $request->file('image'),
                    'groups',
                    800,
                    800
                );
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
            }
        }

        $group = Group::create([
            'created_by' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'visibility' => $request->visibility,
            'image' => $imagePath,
            'members_count' => 1,
        ]);

        // Créer l'adhésion admin pour le créateur
        GroupMembership::create([
            'user_id' => auth()->id(),
            'group_id' => $group->id,
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Groupe créé avec succès !');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Group $group)
    {
        $this->authorize('update', $group);

        $categories = [
            'academic' => 'Académique',
            'club' => 'Club & Association',
            'project' => 'Projet',
            'career' => 'Carrière',
            'other' => 'Autre'
        ];

        return view('groups.edit', compact('group', 'categories'));
    }

    /**
     * Mettre à jour un groupe
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $request->validate([
            'name' => 'required|string|max:100|unique:groups,name,' . $group->id,
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:academic,club,project,career,other',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = $group->image;
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($group->image && !str_starts_with($group->image, 'http')) {
                Storage::disk('public')->delete($group->image);
            }

            try {
                $imagePath = ImageUploadService::upload(
                    $request->file('image'),
                    'groups',
                    800,
                    800
                );
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
            }
        }

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'visibility' => $request->visibility,
            'image' => $imagePath,
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Groupe mis à jour avec succès !');
    }

    /**
     * Supprimer un groupe
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        if ($group->image && !str_starts_with($group->image, 'http')) {
            Storage::disk('public')->delete($group->image);
        }

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Groupe supprimé avec succès.');
    }

    /**
     * Rejoindre un groupe
     */
    public function join(Group $group)
    {
        $user = auth()->user();

        // Vérifier si déjà membre ou demande en attente
        if ($group->isMember($user) || $group->hasPendingRequest($user)) {
            return back()->with('error', 'Vous êtes déjà membre ou avez une demande en attente.');
        }

        $status = $group->visibility === 'public' ? 'approved' : 'pending';

        GroupMembership::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => 'member',
            'status' => $status,
            'joined_at' => $status === 'approved' ? now() : null,
        ]);

        // Mettre à jour le compteur
        if ($status === 'approved') {
            $group->increment('members_count');
            $message = 'Vous avez rejoint le groupe !';
        } else {
            $message = 'Demande envoyée. En attente d\'approbation.';
        }

        return back()->with('success', $message);
    }

    /**
     * Quitter un groupe
     */
    public function leave(Group $group)
    {
        $user = auth()->user();

        if (!$group->isMember($user)) {
            return back()->with('error', 'Vous n\'êtes pas membre de ce groupe.');
        }

        // Empêcher le créateur de quitter s'il est le seul admin
        if ($group->isAdmin($user) && $group->created_by === $user->id) {
            $adminCount = $group->memberships()
                ->where('role', 'admin')
                ->where('status', 'approved')
                ->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'Vous devez désigner un nouvel admin avant de quitter.');
            }
        }

        GroupMembership::where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->delete();

        $group->decrement('members_count');

        return redirect()->route('groups.index')
            ->with('success', 'Vous avez quitté le groupe.');
    }

    /**
     * Liste des membres d'un groupe (admin/modérateur)
     */
    public function members(Group $group)
    {
        $this->authorize('moderate', $group);

        $members = $group->memberships()
            ->with('user')
            ->latest()
            ->paginate(20);

        $pendingRequests = $group->memberships()
            ->with('user')
            ->pending()
            ->get();

        return view('groups.members', compact('group', 'members', 'pendingRequests'));
    }

    /**
     * Approuver une demande d'adhésion
     */
    public function approveMember(Group $group, GroupMembership $membership)
    {
        $this->authorize('moderate', $group);

        if ($membership->group_id !== $group->id) {
            abort(403);
        }

        if ($membership->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $membership->update([
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        $group->increment('members_count');

        // Notification au nouvel membre
        \App\Models\Notification::create([
            'user_id' => $membership->user_id,
            'type' => 'group_approved',
            'data' => [
                'message' => 'Votre demande pour rejoindre "' . $group->name . '" a été approuvée.',
                'group_id' => $group->id,
            ],
        ]);

        return back()->with('success', 'Membre approuvé avec succès.');
    }

    /**
     * Rejeter/Refuser une demande
     */
    public function rejectMember(Group $group, GroupMembership $membership)
    {
        $this->authorize('moderate', $group);

        if ($membership->group_id !== $group->id) {
            abort(403);
        }

        $membership->delete();

        return back()->with('success', 'Demande rejetée.');
    }

    /**
     * Changer le rôle d'un membre
     */
    public function updateMemberRole(Request $request, Group $group, GroupMembership $membership)
    {
        $this->authorize('admin', $group);

        if ($membership->group_id !== $group->id) {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:admin,moderator,member',
        ]);

        // Empêcher de se rétrograder soi-même si c'est le dernier admin
        if ($membership->user_id === auth()->id() && $request->role !== 'admin') {
            $adminCount = $group->memberships()
                ->where('role', 'admin')
                ->where('status', 'approved')
                ->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'Vous devez désigner un nouvel admin avant de changer votre rôle.');
            }
        }

        $membership->update(['role' => $request->role]);

        return back()->with('success', 'Rôle mis à jour.');
    }

    /**
     * Retirer/Expulser un membre
     */
    public function removeMember(Group $group, GroupMembership $membership)
    {
        $this->authorize('moderate', $group);

        if ($membership->group_id !== $group->id) {
            abort(403);
        }

        // Empêcher de s'expulser soi-même via cette méthode
        if ($membership->user_id === auth()->id()) {
            return back()->with('error', 'Utilisez "Quitter le groupe" pour partir.');
        }

        // Empêcher un modérateur d'expulser un admin
        if ($membership->role === 'admin' && !$group->isAdmin(auth()->user())) {
            return back()->with('error', 'Seul un admin peut expulser un autre admin.');
        }

        $wasApproved = $membership->status === 'approved';
        $membership->delete();

        if ($wasApproved) {
            $group->decrement('members_count');
        }

        return back()->with('success', 'Membre retiré du groupe.');
    }

    /**
     * Suggestions de groupes pour l'utilisateur
     */
    public function suggestions()
    {
        $user = auth()->user();

        // IDs des groupes où l'utilisateur est déjà membre ou a une demande
        $memberGroupIds = $user->groupMemberships()->pluck('group_id');

        // Groupes suggérés : même catégorie d'intérêts ou département
        $suggestedGroups = Group::public()
            ->whereNotIn('id', $memberGroupIds)
            ->withCount(['members as approved_members_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->latest()
            ->take(6)
            ->get();

        return view('groups.suggestions', compact('suggestedGroups'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter') === 'unread' ? 'unread' : 'all';

        $messages = ContactMessage::query()
            ->when($filter === 'unread', fn ($query) => $query->unread())
            ->latest()
            ->get();

        return view('admin.messages.index', [
            'messages' => $messages,
            'filter' => $filter,
            'total' => ContactMessage::count(),
            'unreadCount' => ContactMessage::unread()->count(),
        ]);
    }

    public function show(ContactMessage $message)
    {
        if ($message->read_at === null) {
            $message->forceFill(['read_at' => now()])->save();
        }

        return view('admin.messages.show', [
            'message' => $message,
            'total' => ContactMessage::count(),
            'unreadCount' => ContactMessage::unread()->count(),
        ]);
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
    }

    public function markRead(ContactMessage $message)
    {
        $message->forceFill(['read_at' => now()])->save();

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function markUnread(ContactMessage $message)
    {
        $message->forceFill(['read_at' => null])->save();

        return back()->with('success', 'Pesan ditandai belum dibaca.');
    }
}

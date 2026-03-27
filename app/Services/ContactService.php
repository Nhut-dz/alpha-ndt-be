<?php

namespace App\Services;

use App\Models\ContactModel;

class ContactService
{
    public function getAll(): array
    {
        $contacts = ContactModel::orderBy('created_at', 'desc')->get();

        return [
            'success' => true,
            'data' => $contacts,
        ];
    }

    public function show(int $id): array
    {
        $contact = ContactModel::find($id);

        if (!$contact) {
            return [
                'success' => false,
                'message' => 'Liên hệ không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => $contact,
        ];
    }

    public function updateStatus(int $id, int $status): array
    {
        $contact = ContactModel::find($id);

        if (!$contact) {
            return [
                'success' => false,
                'message' => 'Liên hệ không tồn tại.',
            ];
        }

        $contact->update(['status' => $status]);

        return [
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'data' => $contact,
        ];
    }

    public function delete(int $id): array
    {
        $contact = ContactModel::find($id);

        if (!$contact) {
            return [
                'success' => false,
                'message' => 'Liên hệ không tồn tại.',
            ];
        }

        $contact->delete();

        return [
            'success' => true,
            'message' => 'Xóa liên hệ thành công.',
        ];
    }
}

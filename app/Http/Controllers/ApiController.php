<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Product;
use Laravel\Lumen\Routing\Controller;
use App\Http\Middleware\AuthenticateWithBearerToken;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(AuthenticateWithBearerToken::class);
    }

    // CRUD methods for user
    public function getUsers(Request $request)
    {

        
        $take = $request->query('take', 10);
        $skip = $request->query('skip', 0);
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
        }

        $users = $query->skip($skip)->take($take)->get();

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $users
        ]);
    }

    public function createUser(Request $request)
    {
        $validator = $this->validateUserParams($request);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $validator = $this->validateUserParams($request);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $user->delete();

        return response()->json([
            'code' => 200,
            'message' => 'User soft deleted successfully',
            'data' => null
        ]);
    }

    // CRUD methods for product
    public function getProducts(Request $request)
    {
        $take = $request->query('take', 10);
        $skip = $request->query('skip', 0);
        $search = $request->query('search');

        $query = Product::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $users = $query->skip($skip)->take($take)->get();

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $users
        ]);
    }

    public function createProduct(Request $request)
    {
        $validator = $this->validateProductParams($request);
    
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }
    
        // Dapatkan pengguna saat ini dari permintaan
        $user = $request->user();
    
        // Buat produk yang terkait dengan pengguna saat ini
        $product = $user->products()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price')
        ]);
    
        return response()->json([
            'code' => 201,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }
    

    public function updateProduct(Request $request, $id)
    {
        $validator = $this->validateProductParams($request);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'code' => 200,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }

        $product->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Product soft deleted successfully',
            'data' => null
        ]);
    }

    // Implementasi validasi parameter
    private function validateUserParams($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        return $validator;
    }

    private function validateProductParams($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);

        return $validator;
    }
}

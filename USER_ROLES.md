# Lietotāju Lomas / User Roles

## 📋 Sistēmas Lomas

### 1. 🛡️ Admin (Administrators)
**Pieslēgšanās:**
- Email: `admin@admin.com`
- Parole: `admin`

**Tiesības:**
- ✅ Var apskatīt visas grāmatas
- ✅ Var pievienot jaunas grāmatas
- ✅ Var dzēst grāmatas
- ✅ Var izņemt un atgriezt grāmatas
- ✅ Var apskatīt profilu
- ✅ Pilna piekļuve visām funkcijām

**Navigācija:**
- Sākums
- Bibliotēka
- Meklēt
- **Pievienot Grāmatas** (tikai adminiem!)
- Par Mums
- Profils (klikšķinot uz vārda)
- 🛡️ Admin nozīmīte

---

### 2. 👤 User (Lietotājs)
**Pieslēgšanās piemēri:**
- Email: `test@example.com` / Parole: `password`
- Email: `john@example.com` / Parole: `password`
- Email: `jane@example.com` / Parole: `password`

**Tiesības:**
- ✅ Var apskatīt visas grāmatas
- ✅ Var meklēt grāmatas
- ✅ Var izņemt grāmatas
- ✅ Var atgriezt savas grāmatas
- ✅ Var apskatīt savu profilu un aizņēmumu vēsturi
- ❌ **Nevar** pievienot jaunas grāmatas
- ❌ **Nevar** dzēst grāmatas

**Navigācija:**
- Sākums
- Bibliotēka
- Meklēt
- Par Mums
- Profils (klikšķinot uz vārda)
- 👤 Lietotājs nozīmīte

---

### 3. 👁️ Guest (Viesis)
**Statuss:** Nepierakstīts lietotājs (nav autentificēts)

**Tiesības:**
- ✅ Var apskatīt sākumlapu
- ✅ Var meklēt grāmatas (tikai skatīšanās režīmā)
- ✅ Var lasīt "Par Mums"
- ✅ Var mainīt valodu (EN/LV)
- ❌ **Nevar** apskatīt bibliotēku
- ❌ **Nevar** izņemt grāmatas
- ❌ **Nevar** pievienot grāmatas
- ❌ **Nevar** apskatīt profilu

**Navigācija:**
- Sākums
- Meklēt
- Par Mums
- 👁️ Viesis nozīmīte
- Ieiet (Login poga)

---

## 🔐 Lomas Kods

### Model (User.php)
```php
// Pārbauda vai lietotājs ir administrators
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

// Pārbauda vai lietotājs ir parastais lietotājs
public function isUser(): bool
{
    return $this->role === 'user';
}

// Pārbauda vai apmeklētājs ir viesis (nav pierakstījies)
public static function isGuest(): bool
{
    return !auth()->check();
}
```

### Blade Template Izmantošana
```php
@auth
    {{-- Pierakstīts lietotājs (Admin vai User) --}}
    @if(auth()->user()->isAdmin())
        {{-- Tikai adminiem --}}
        <a href="/books/create">Pievienot Grāmatas</a>
    @endif
@else
    {{-- Viesis (Guest) --}}
    <span>👁️ Viesis</span>
@endauth
```

---

## 📊 Lomu Salīdzinājums

| Funkcionalitāte | Admin | User | Guest |
|----------------|-------|------|-------|
| Skatīt grāmatas | ✅ | ✅ | ⚠️ Ierobežoti |
| Meklēt grāmatas | ✅ | ✅ | ✅ |
| Izņemt grāmatas | ✅ | ✅ | ❌ |
| Atgriezt grāmatas | ✅ | ✅ | ❌ |
| Pievienot grāmatas | ✅ | ❌ | ❌ |
| Dzēst grāmatas | ✅ | ❌ | ❌ |
| Apskatīt profilu | ✅ | ✅ | ❌ |
| Apskatīt bibliotēku | ✅ | ✅ | ❌ |

---

## 🚀 Kā Testēt Lomas

### Testēt Guest:
1. Atvēr inkognito/privāto logu
2. Dodies uz http://127.0.0.1:8001
3. Redzi "👁️ Viesis" nozīmīti
4. Mēģini noklikšķināt uz "Bibliotēka" - būs jāpierakstās

### Testēt User:
1. Pieraksties ar `test@example.com` / `password`
2. Redzi "👤 Lietotājs" nozīmīti
3. Nav redzama "Pievienot Grāmatas" poga
4. Vari izņemt un atgriezt grāmatas

### Testēt Admin:
1. Pieraksties ar `admin@admin.com` / `admin`
2. Redzi "🛡️ Admin" nozīmīti
3. Redzama "Pievienot Grāmatas" poga
4. Vari dzēst grāmatas (sarkana poga)

---

## 🔄 Lomas Maiņa

Lai mainītu lietotāja lomu:
```bash
# Atver Tinker
php artisan tinker

# Maina lietotāja lomu
$user = User::where('email', 'test@example.com')->first();
$user->role = 'admin';
$user->save();
```

Vai izmantojot MySQL:
```sql
UPDATE users SET role = 'admin' WHERE email = 'test@example.com';
```

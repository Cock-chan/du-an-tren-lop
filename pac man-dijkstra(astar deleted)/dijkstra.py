import heapq

# =========================
# DIJKSTRA: HƯỚNG ĐI TIẾP THEO CỦA GHOST
# =========================
def dijkstra_next_direction(ghost, pacman, maze):

    # 4 hướng di chuyển: phải, trái, xuống, lên
    directions = [(1,0), (-1,0), (0,1), (0,-1)]

    # Kích thước mê cung
    ROWS, COLS = len(maze), len(maze[0])

    # Mảng lưu khoảng cách ngắn nhất
    dist = [[float('inf') for _ in range(COLS)] for _ in range(ROWS)]

    # Mảng lưu ô trước đó (để truy vết đường đi)
    prev = [[None for _ in range(COLS)] for _ in range(ROWS)]

    # Priority Queue cho Dijkstra
    heap = []

    # Bắt đầu từ vị trí ghost
    heapq.heappush(heap, (0, ghost.x, ghost.y))
    dist[ghost.y][ghost.x] = 0

    # =========================
    # VÒNG LẶP DIJKSTRA
    # =========================
    while heap:
        cost, x, y = heapq.heappop(heap)

        # Nếu đã tới Pac-Man thì dừng
        if (x, y) == (pacman.x, pacman.y):
            break

        # Duyệt 4 hướng
        for d in directions:
            nx, ny = x + d[0], y + d[1]

            # Kiểm tra hợp lệ và không phải tường
            if 0 <= nx < COLS and 0 <= ny < ROWS and maze[ny][nx] != 1:
                new_cost = cost + 1

                # Nếu tìm được đường ngắn hơn
                if new_cost < dist[ny][nx]:
                    dist[ny][nx] = new_cost
                    prev[ny][nx] = (x, y)
                    heapq.heappush(heap, (new_cost, nx, ny))

    # =========================
    # TRUY VẾT ĐƯỜNG ĐI
    # (CHỈ LẤY BƯỚC ĐẦU TIÊN)
    # =========================
    path = []
    x, y = pacman.x, pacman.y

    while prev[y][x] is not None and (x, y) != (ghost.x, ghost.y):
        px, py = prev[y][x]

        # Vector hướng di chuyển
        path.append((x - px, y - py))

        x, y = px, py

    if path:
        # Trả về bước đầu tiên từ ghost tới Pac-Man
        return path[-1]
    else:
        # Không tìm được đường hoặc đã trùng vị trí
        return ghost.dir

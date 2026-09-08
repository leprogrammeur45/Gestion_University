#!/bin/sh

set -e

IMAGE="papamamadoudiouf/gestion-university"
REPO_ROOT=$(pwd)
WORKTREE_DIR="/tmp/gestion-university-release"

REUSSIS=""
ECHECS=""

# Dockerfile de référence : celui de la version actuelle
DOCKERFILE_SOURCE="$REPO_ROOT/Dockerfile"

# Vérifier que le Dockerfile de référence existe
if [ ! -f "$DOCKERFILE_SOURCE" ]; then
    echo "Erreur : Dockerfile absent à la racine du projet."
    exit 1
fi

# Nettoyer un éventuel ancien worktree
rm -rf "$WORKTREE_DIR"

for TAG in $(git tag --sort=version:refname); do

    echo ""
    echo "========================================"
    echo "=== $TAG ==="
    echo "========================================"

    # Créer un worktree correspondant exactement au tag
    git worktree add --detach "$WORKTREE_DIR" "$TAG" > /dev/null 2>&1

    # Si le tag ne possède pas son propre Dockerfile,
    # utiliser celui de la version actuelle comme Dockerfile de référence.
    if [ ! -f "$WORKTREE_DIR/Dockerfile" ]; then
        echo "Dockerfile absent dans $TAG."
        echo "Utilisation du Dockerfile de référence."

        cp "$DOCKERFILE_SOURCE" "$WORKTREE_DIR/Dockerfile"
    else
        echo "Dockerfile présent dans $TAG."
    fi

    echo "Construction de ${IMAGE}:${TAG}..."

    # Construire l'image avec le code du tag
    if ! (
        cd "$WORKTREE_DIR" &&
        docker build -t "${IMAGE}:${TAG}" .
    ); then

        echo "Échec du build pour $TAG."
        ECHECS="$ECHECS $TAG"

        git worktree remove --force "$WORKTREE_DIR"
        continue
    fi

    echo "Build réussi pour $TAG."

    # Push Docker Hub
    echo "Push de ${IMAGE}:${TAG}..."

    if ! docker push "${IMAGE}:${TAG}"; then
        echo "Échec du push pour $TAG."
        ECHECS="$ECHECS $TAG"

        git worktree remove --force "$WORKTREE_DIR"
        continue
    fi

    echo "Push réussi pour $TAG."

    REUSSIS="$REUSSIS $TAG"

    # Supprimer le worktree temporaire
    git worktree remove --force "$WORKTREE_DIR"
done

# Déterminer le dernier tag construit avec succès
DERNIER_TAG=$(echo "$REUSSIS" | tr ' ' '\n' | tail -1)

if [ -n "$DERNIER_TAG" ]; then

    echo ""
    echo "========================================"
    echo "=== latest ==="
    echo "========================================"

    docker tag "${IMAGE}:${DERNIER_TAG}" "${IMAGE}:latest"

    docker push "${IMAGE}:latest"

    echo "Image latest poussée avec succès."
fi

echo ""
echo "========================================"
echo "=== Résumé ==="
echo "========================================"

echo "Images poussées :${REUSSIS}"
echo "Tags en échec   :${ECHECS}"